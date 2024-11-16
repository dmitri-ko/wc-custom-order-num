<?php
/**
 * This file is part of the Woocommerce Custom Order plugin.
 *
 * (c) Walger Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author     Walger Marketing
 * @package    CON
 * @subpackage Subscriber
 */

namespace DKO\CON\Subscriber;

use DKO\CON\EventManagement\Subscriber_Interface;
use DKO\CON\Entity\Customer_Order;

/**
 * Class Search_Subscriber
 *
 * This class subscribes to WooCommerce events to add custom search fields and modify order queries.
 */
class Search_Subscriber implements Subscriber_Interface {


	/**
	 * Get the events that this subscriber wants to listen to.
	 *
	 * @return array
	 */
	public static function get_subscribed_events() {
		return array(
			'woocommerce_hpos_admin_search_filters' => 'add_custom_search_fields',
			'woocommerce_order_query'               => array( 'order_query', 10, 2 ),
		);
	}

	/**
	 * Add custom search fields to WooCommerce shop orders.
	 *
	 * @param array $search_fields The existing search fields.
	 * @return array The modified search fields.
	 */
	public function add_custom_search_fields( array $search_fields ): array {
		$search_fields = array_merge(
			array_slice( $search_fields, 0, array_search( 'order_id', array_keys( $search_fields ), true ) + 1 ),
			array(
				'order_number' => 'Bestellnummer',
			),
			array_slice( $search_fields, array_search( 'order_id', array_keys( $search_fields ), true ) + 1 )
		);
		return $search_fields;
	}

	/**
	 * Modify the order query based on custom search fields.
	 *
	 * @param mixed $result The existing result.
	 * @param array $args The query arguments.
	 * @return mixed The modified result.
	 */
	public function order_query( $result, $args ) {
		if ( empty( $args['s'] ) || ! in_array( $args['search_filter'], array( 'all', 'order_number' ), true ) ) {
			return $result;
		}

		$prefix  = \WC_Admin_Settings::get_option( 'prefix' );
		$postfix = \WC_Admin_Settings::get_option( 'postfix' );
		$seed    = (int) str_replace( array( $prefix, $postfix ), '', $args['s'] );

		$meta_query = array(
			array(
				'key'   => Customer_Order::CON_META_KEY,
				'value' => $seed,
			),
		);

		$args['meta_query'] = array_merge( $args['meta_query'] ?? array(), $meta_query );
		unset( $args['s'] );

		remove_filter( 'woocommerce_order_query', array( $this, 'order_query' ) );
		$extra_results = wc_get_orders( $args );
		add_filter( 'woocommerce_order_query', array( $this, 'order_query' ), 10, 2 );

		if ( $extra_results && ! empty( $extra_results->orders ) ) {
			$result->orders = array_merge( $result->orders, $extra_results->orders );
			$result->total  = count( $result->orders );
		}

		return $result;
	}
}
