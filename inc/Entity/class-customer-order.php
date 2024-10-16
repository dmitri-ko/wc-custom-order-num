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

	const CON_META_KEY = '_order_num';

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
	 * @param int $order_id The order ID.
	 */
	public function __construct( int $order_id ) {
		$this->order = wc_get_order( $order_id );
		$this->store = array();
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
	 * Get order number
	 *
	 * @return string
	 */
	public function get_order_num(): string {
		if ( ! $this->is_valid() ) {
			return '';
		}
		$prefix     = \WC_Admin_Settings::get_option( 'prefix' );
		$postfix    = \WC_Admin_Settings::get_option( 'postfix' );
		$num_length = \WC_Admin_Settings::get_option( 'num_length' );
		$custom_num = $this->get_meta_with_default( self::CON_META_KEY, false );

		return $custom_num ? ( $prefix . sprintf( '%0' . $num_length . 'd', $custom_num ) . $postfix ) : $this->get_id();
	}

	/**
	 * Get next available order num
	 *
	 * @return int
	 */
	public static function get_next_order_num(): int {
		$custom_num = 0;

			$orders = wc_get_orders(
				array(
					'meta_query' => array(
						array(
							'key'     => self::CON_META_KEY,
							'compare' => 'EXISTS',
						),
					),
					'orderby'    => 'date_created',
					'order'      => 'DESC',
				)
			);
		if ( count( $orders ) ) {
			$last_order = $orders[0];
			$custom_num = $last_order->get_meta( self::CON_META_KEY );
			++$custom_num;
		} else {
			$custom_num = \WC_Admin_Settings::get_option( 'start_num' );
		}

		return $custom_num;
	}

	/**
	 * Set order number
	 *
	 * @param  string $order_num The order number.
	 *
	 * @return void
	 */
	public function set_order_num( string $order_num ) {
		$this->store[ self::CON_META_KEY ] = $order_num;
	}

	/**
	 * Reset order number
	 *
	 * @return void
	 */
	public function reset_order_num() {
		$this->remove_meta( self::CON_META_KEY );
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
	 * Check object concistency
	 *
	 * @return bool
	 */
	private function is_valid(): bool {
		return ! empty( $this->order );
	}
}
