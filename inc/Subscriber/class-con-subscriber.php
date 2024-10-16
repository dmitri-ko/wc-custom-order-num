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
 * @subpackage CON/subscriber
 */

namespace DKO\CON\Subscriber;

use DKO\CON\Entity\Customer_Order;
use DKO\CON\EventManagement\Subscriber_Interface;

/**
 * Event subscriber that registers custom order number.
 *
 * @author Walger Marketing
 */
class CON_Subscriber implements Subscriber_Interface {

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'woocommerce_order_number' => 'show_number',
			'woocommerce_new_order'    => array( 'generate_custom_num', 10, 2 ),
		);
	}


	/**
	 * Show custom order number
	 *
	 * @param  int $order_id The order ID.
	 *
	 * @return string
	 */
	public function show_number( int $order_id ): string {
		$customer_order = new Customer_Order( $order_id );
		return $customer_order->get_order_num();
	}

	/**
	 * Generate custom order number for WC_Order
	 *
	 * @param  int       $order_id The order ID.
	 * @param  \WC_Order $order The order.
	 *
	 * @return void
	 */
	public function generate_custom_num( int $order_id, \WC_Order $order ) {
		$custom_num = Customer_Order::get_next_order_num();
		$order->update_meta_data( Customer_Order::CON_META_KEY, $custom_num );
	}
}
