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

use DKO\CON\Service\CON_JSON_Reader;

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
	 * @param  \WC_Order|\WP_Post $post_or_order_object The order or order ID.
	 */
	public function __construct( \WC_Order|\WP_Post $post_or_order_object ) {
		$this->order = ( $post_or_order_object instanceof \WP_Post ) ? wc_get_order( $post_or_order_object->ID ) : $post_or_order_object;
		$this->store = array();
	}

	/**
	 * Gets the order ID
	 *
	 * @return int/string
	 */
	public function get_id() {
		return $this->order->get_id();
	}


	/**
	 * Get order number
	 *
	 * @param string $default_value  the default metadata value if value is not set.
	 * @return string
	 */
	public function get_order_num( string $default_value = '' ): string {
		return $this->get_meta_with_default( self::CON_META_KEY, $default_value );
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
	 * Generate order number
	 *
	 * @param  int $num The order number.
	 *
	 * @return void
	 */
	public function generate_order_num( int $num ) {
		$prefix     = \WC_Admin_Settings::get_option( 'prefix' );
		$postfix    = \WC_Admin_Settings::get_option( 'postfix' );
		$num_length = \WC_Admin_Settings::get_option( 'num_length' );

		$this->store[ self::CON_META_KEY ] = $prefix . sprintf( '%0' . $num_length . 'd', $num ) . $postfix;
		$this->save_meta();
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
		if ( ! isset( $this->store[ $meta_key ] ) ) {
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
		foreach ( $this->store as $meta_key => $meta_value ) {
			$this->order->update_meta_data( $meta_key, $meta_value );
		}
		$this->order->save();
	}

	/**
	 * Remove meta
	 *
	 * @param  string $key The meta key.
	 * @return void
	 */
	protected function remove_meta( string $key ) {
		unset( $this->store[ $key ] );
		$this->order->delete_meta_data( $key );
		$this->order->save();
	}
}
