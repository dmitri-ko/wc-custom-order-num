<?php
/**
 * This file is part of the Woocommerce Custom Order Number plugin.
 *
 * (c) Walger Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author     Walger Marketing
 * @package    CON
 * @subpackage CON/core
 */

namespace DKO\CON\EventManagement;

/**
 * A Subscriber knows what specific WordPress events it wants to listen to.
 *
 * When an EventManager adds a Subscriber, it gets all the WordPress events that
 * it wants to listen to. It then adds the subscriber as a listener for each of them.
 *
 * @author Walger Marketing
 */
interface Subscriber_Interface {


	/**
	 * Returns an array of events that this subscriber wants to listen to.
	 *
	 * The array key is the event name. The value can be:
	 *
	 *  * The method name
	 *  * An array with the method name and priority
	 *  * An array with the method name, priority and number of accepted arguments
	 *
	 * For instance:
	 *
	 *  * array('hook_name' => 'method_name')
	 *  * array('hook_name' => array('method_name', $priority))
	 *  * array('hook_name' => array('method_name', $priority, $accepted_args))
	 *
	 * @return array
	 */
	public static function get_subscribed_events();
}
