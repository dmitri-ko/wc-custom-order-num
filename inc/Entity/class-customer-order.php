<?php
/**
 * This file is part of the Dog Customer tests plugin.
 *
 * (c) Walger Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author     Walger Marketing
 * @package    CON
 * @subpackage CON/subscriber
 */

namespace DKO\CON\Entity;

/**
 * WC Order with custom order number support
 *
 * @author Walger Marketing
 */
class Customer_Order {

	const CON_META_KEY_ORDER_NUM_SEED    = '_order_num';
	const CON_META_KEY_DISPLAY_ORDER_NUM = '_displayed_order_num';
	const CON_TRANSIENT_KEY              = 'wc_custom_order_num_free_num';

	/**
	 * The Order object for Customer order
	 *
	 * @var \WC_Order|\WC_Order_Refund
	 */
	protected $order;

	/**
	 * The meta cache
	 *
	 * @var array
	 */
	protected $store;

	/**
	 * Create Customer order
	 *
	 * @param \WC_Order $order The WooCommerce order object.
	 */
	public function __construct( \WC_Order $order ) {
		$this->order = $order;
		$this->store = array();
	}

	/**
	 * Constructs the order number based on the seed, prefix, and postfix.
	 *
	 * @return string The constructed order number.
	 */
	public function construct_order_num(): string {
		if ( ! $this->is_valid() ) {
			return '';
		}

		try {
			$custom_num = $this->get_order_num_seed();
		} catch ( \Exception $e ) {
			return $this->get_id();
		}
		try {
			$settings = $this->get_historic_settings();
		} catch ( \Exception $e ) {
			return $this->get_id();
		}

		$prefix = $settings['prefix'];
		if ( strpos( $prefix, 'YY' ) !== false ) {
			$prefix = str_replace( 'YY', gmdate( 'y' ), $prefix );
		}
		$postfix    = $settings['postfix'];
		$num_length = $settings['num_length'];

		return $prefix . sprintf( '%0' . $num_length . 'd', $custom_num ) . $postfix;
	}

	/**
	 * Get order number
	 *
	 * @return string
	 */
	public function get_order_num(): string {
		if ( ! $this->is_valid() ) {
			return '';
		}

		return $this->get_meta_with_default( self::CON_META_KEY_DISPLAY_ORDER_NUM, false );
	}

	/**
	 * Get order number seed
	 *
	 * @return string
	 * @throws \Exception If the order number seed does not exist or is empty.
	 */
	public function get_order_num_seed(): string {
		if ( ! $this->is_valid() ) {
			return '';
		}
		$custom_num = $this->get_meta_with_default( self::CON_META_KEY_ORDER_NUM_SEED, false );
		if ( empty( $custom_num ) ) {
			throw new \Exception( 'Order number seed does not exist or is empty.' );
		}

		return $custom_num;
	}

	/**
	 * Get next available order num
	 *
	 * @return int
	 */
	public static function get_next_order_num(): int {

		$custom_num = get_transient( self::CON_TRANSIENT_KEY );

		if ( false === $custom_num ) {
			$start_date = \WC_Admin_Settings::get_option( 'start_date' );
			$orders     = wc_get_orders(
				array(
					'meta_query' => array(
						array(
							'key'     => self::CON_META_KEY_ORDER_NUM_SEED,
							'compare' => 'EXISTS',
						),
					),
					'date_query' => array(
						array(
							'after'     => $start_date,
							'inclusive' => true,
						),
					),
					'orderby'    => 'date_created',
					'order'      => 'DESC',
				)
			);
			$prefix     = \WC_Admin_Settings::get_option( 'prefix' );
			if ( strpos( $prefix, 'YY' ) !== false ) {
				$current_year = gmdate( 'Y' );
				$orders       = array_filter(
					$orders,
					function ( $order ) use ( $current_year ) {
						return gmdate( 'Y', strtotime( $order->get_date_created() ) ) === $current_year;
					}
				);
			}
			if ( count( $orders ) ) {
				$last_order = new Customer_Order( $orders[0] );
				$custom_num = $last_order->get_order_num_seed();
				++$custom_num;
			} else {
				$custom_num = \WC_Admin_Settings::get_option( 'start_num' );
			}
			set_transient( self::CON_TRANSIENT_KEY, $custom_num, DAY_IN_SECONDS );
		} else {
			++$custom_num;
			set_transient( self::CON_TRANSIENT_KEY, $custom_num, DAY_IN_SECONDS );
		}
		return $custom_num;
	}

	/**
	 * Gets the order ID
	 *
	 * @return int
	 */
	public function get_id(): int {
		return $this->is_valid() ? $this->order->get_id() : 0;
	}

	/**
	 * Gets order metadata from the cache
	 *
	 * @param string $meta_key the order metadata key.
	 *
	 * @return mixed
	 */
	protected function get_cached_meta( $meta_key ) {
		if ( ! isset( $this->store[ $meta_key ] ) && $this->is_valid() ) {
			$this->store[ $meta_key ] = $this->order->get_meta( $meta_key );
		}

		return $this->store[ $meta_key ];
	}

	/**
	 * Gets order metadata
	 *
	 * @param string       $meta_key the order metadata key.
	 * @param mixed|string $default_value  the default metadata value if value is not set.
	 *
	 * @return mixed|string
	 */
	protected function get_meta_with_default( $meta_key, $default_value = '' ) {
		$maybe_empty_value = $this->get_cached_meta( $meta_key );
		return empty( $maybe_empty_value ) ? $default_value : $maybe_empty_value;
	}

	/**
	 * Check object concistency
	 *
	 * @return bool
	 */
	private function is_valid(): bool {
		return ! empty( $this->order );
	}

	/**
	 * Remove meta
	 *
	 * @param  string $key The meta key.
	 * @return void
	 */
	protected function remove_meta( string $key ) {
		if ( $this->is_valid() ) {
			unset( $this->store[ $key ] );
			$this->order->delete_meta_data( $key );
			$this->order->save();
		}
	}

	/**
	 * Reset order number
	 *
	 * @return void
	 */
	public function reset_order_num() {
		$this->remove_meta( self::CON_META_KEY_ORDER_NUM_SEED );
	}

	/**
	 * Save meta
	 *
	 * @return void
	 */
	public function save_meta() {
		if ( $this->is_valid() ) {
			foreach ( $this->store as $meta_key => $meta_value ) {
				$this->order->update_meta_data( $meta_key, $meta_value );
			}
			$this->order->save();
		}
	}

	/**
	 * Set the order number.
	 *
	 * @param string $order_num The order number.
	 */
	public function set_order_number( string $order_num ) {
		$this->store[ self::CON_META_KEY_DISPLAY_ORDER_NUM ] = $order_num;
	}

	/**
	 * Set order number seed
	 *
	 * @param string $order_num_seed The order number seed.
	 *
	 * @return void
	 */
	public function set_order_num_seed( string $order_num_seed ) {
		$this->store[ self::CON_META_KEY_ORDER_NUM_SEED ]    = $order_num_seed;
		$this->store[ self::CON_META_KEY_DISPLAY_ORDER_NUM ] = $this->construct_order_num();
	}

	/**
	 * Retrieve historical settings data for a given order creation date.
	 *
	 * @return array|null Returns an array with 'start_date', 'prefix', 'postfix' or null if not found.
	 * @throws \Exception If historical settings data is not found.
	 */
	private function get_historic_settings(): ?array {
		if ( ! $this->is_valid() ) {
			return null;
		}

		$setting_keys       = array( 'start_date', 'prefix', 'postfix', 'num_length' );
		$order_date         = $this->order->get_date_created() ?? new \WC_DateTime();
		$current_start_date = \WC_Admin_Settings::get_option( 'start_date' );
		$history_data       = array();

		// Retrieve historical settings data.
		foreach ( $setting_keys as $key ) {
			$history = \WC_Admin_Settings::get_option( $key . '_history', array() );

			if ( ! is_array( $history ) || empty( $history ) ) {
				continue; // Skip if no history is available.
			}

			// Store history by timestamp.
			foreach ( $history as $entry ) {
				$timestamp = $entry['timestamp'];
				if ( ! isset( $history_data[ $timestamp ] ) ) {
					$history_data[ $timestamp ] = array();
				}
				$history_data[ $timestamp ][ $key ] = $entry['value'];
			}
		}

		// Sort history by timestamp (oldest to newest).
		ksort( $history_data );

		$timestamps = array_keys( $history_data );

		foreach ( $timestamps as $index => $timestamp ) {
			$start_date      = $history_data[ $timestamp ]['start_date'] ?? null;
			$next_start_date = isset( $timestamps[ $index + 1 ] ) ? ( $history_data[ $timestamps[ $index + 1 ] ]['start_date'] ?? $current_start_date ) : $current_start_date;

			if ( $start_date ) {
				$start_date_obj      = new \WC_DateTime( $start_date );
				$next_start_date_obj = new \WC_DateTime( $next_start_date );

				if ( $order_date >= $start_date_obj && $order_date < $next_start_date_obj ) {
					return array(
						'start_date' => $start_date,
						'prefix'     => $history_data[ $timestamp ]['prefix'] ?? '',
						'postfix'    => $history_data[ $timestamp ]['postfix'] ?? '',
						'num_length' => $history_data[ $timestamp ]['num_length'] ?? 0,
					);
				}
			}
		}
		if ( $order_date >= new \WC_DateTime( $current_start_date ) ) {
			return array(
				'start_date' => $current_start_date,
				'prefix'     => \WC_Admin_Settings::get_option( 'prefix' ),
				'postfix'    => \WC_Admin_Settings::get_option( 'postfix' ),
				'num_length' => \WC_Admin_Settings::get_option( 'num_length' ),
			);
		}

		throw new \Exception( 'Historical settings data not found.' );
	}
}
