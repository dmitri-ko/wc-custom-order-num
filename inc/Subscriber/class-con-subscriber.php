<?php
/**
 * This file is part of the Dog DNA tests plugin.
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
		);
	}


	/**
	 * Undocumented function
	 *
	 * @param  int $order_id
	 *
	 * @return string
	 */
	public function show_number( int $order_id ): string {
		$customer_order = new Customer_Order( wc_get_order( $order_id ) );
		return $customer_order->get_order_num();
	}
}
