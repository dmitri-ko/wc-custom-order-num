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
			'woocommerce_order_number'             => 'show_number',
			'woocommerce_before_order_object_save' => 'generate_custom_num',
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
		$order  = wc_get_order( $order_id );
		if ( ! $order ) {
			return '';
		}
		$customer_order = new Customer_Order( wc_get_order( $order_id ) );
		return $customer_order->get_order_num();
	}

	/**
	 * Generate custom order number while the order is saving
	 *
	 * @param  \WC_Data $maybe_order The order.
	 *
	 * @return void
	 */
	public function generate_custom_num( \WC_Data $maybe_order ): void {
		if ( ! $maybe_order instanceof \WC_Order ) {
			return;
		}

		$customer_order = new Customer_Order( $maybe_order );

		try {
			$customer_order->get_order_num_seed();

		} catch ( \Exception $e ) {
			$customer_order->set_order_num_seed( Customer_Order::get_next_order_num() );
			$customer_order->save_meta();
		}
	}
}
