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
 * The event manager manages events using the WordPress plugin API.
 *
 * @author Walger Marketing
 */
class Event_Manager {

	/**
	 * Adds a callback to a specific hook of the WordPress plugin API.
	 *
	 * @uses add_filter()
	 * @uses add_action()
	 *
	 * @param string   $hook_name     WordPress Plugin API hook name.
	 * @param callable $callback      WordPress Plugin API hook callback.
	 * @param int      $priority      The hook priority.
	 * @param int      $accepted_args Number of accepted arguments.
	 */
	public function add_callback( $hook_name, $callback, $priority = 10, $accepted_args = 1 ) {
		add_filter( $hook_name, $callback, $priority, $accepted_args );
	}

	/**
	 * Add an event subscriber.
	 *
	 * The event manager registers all the hooks that the given subscriber
	 * wants to register with the WordPress Plugin API.
	 *
	 * @param Subscriber_Interface $subscriber Subscriber.
	 */
	public function add_subscriber( Subscriber_Interface $subscriber ) {
		foreach ( $subscriber->get_subscribed_events() as $hook_name => $parameters ) {
			$this->add_subscriber_callback( $subscriber, $hook_name, $parameters );
		}
	}

	/**
	 * Executes all the callbacks registered with the given hook.
	 *
	 * @uses do_action()
	 */
	public function execute() {
		$args = func_get_args();
		return call_user_func_array( 'do_action', $args );
	}

	/**
	 * Filters the given value by applying all the changes from the callbacks
	 * registered with the given hook. Returns the filtered value.
	 *
	 * @uses apply_filters()
	 *
	 * @return mixed
	 */
	public function filter() {
		$args = func_get_args();
		return call_user_func_array( 'apply_filters', $args );
	}

	/**
	 * Get the name of the hook that WordPress plugin API is executing. Returns
	 * false if it isn't executing a hook.
	 *
	 * @uses current_filter()
	 *
	 * @return string|bool
	 */
	public function get_current_hook() {
		return current_filter();
	}

	/**
	 * Checks the WordPress plugin API to see if the given hook has
	 * the given callback. The priority of the callback will be returned
	 * or false. If no callback is given will return true or false if
	 * there's any callbacks registered to the hook.
	 *
	 * @uses has_filter()
	 *
	 * @param string $hook_name The hook name.
	 * @param mixed  $callback  The hook callback.
	 *
	 * @return bool|int
	 */
	public function has_callback( $hook_name, $callback = false ) {
		return has_filter( $hook_name, $callback );
	}

	/**
	 * Removes the given callback from the given hook. The WordPress plugin API only
	 * removes the hook if the callback and priority match a registered hook.
	 *
	 * @uses remove_filter()
	 *
	 * @param string   $hook_name The hook name.
	 * @param callable $callback  The hook callback.
	 * @param int      $priority  The hook priority.
	 *
	 * @return bool
	 */
	public function remove_callback( $hook_name, $callback, $priority = 10 ) {
		return remove_filter( $hook_name, $callback, $priority );
	}

	/**
	 * Remove an event subscriber.
	 *
	 * The event manager removes all the hooks that the given subscriber
	 * wants to register with the WordPress Plugin API.
	 *
	 * @param Subscriber_Interface $subscriber The subscriber.
	 */
	public function remove_subscriber( Subscriber_Interface $subscriber ) {
		foreach ( $subscriber->get_subscribed_events() as $hook_name => $parameters ) {
			$this->remove_subscriber_callback( $subscriber, $hook_name, $parameters );
		}
	}

	/**
	 * Adds the given subscriber's callback to a specific hook
	 * of the WordPress plugin API.
	 *
	 * @param Subscriber_Interface $subscriber The subscriber.
	 * @param string               $hook_name  The hook name.
	 * @param mixed                $parameters The hook parameters.
	 */
	private function add_subscriber_callback( Subscriber_Interface $subscriber, $hook_name, $parameters ) {
		if ( is_string( $parameters ) ) {
			$this->add_callback( $hook_name, array( $subscriber, $parameters ) );
		} elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {
			$this->add_callback( $hook_name, array( $subscriber, $parameters[0] ), isset( $parameters[1] ) ? $parameters[1] : 10, isset( $parameters[2] ) ? $parameters[2] : 1 );
		}
	}

	/**
	 * Removes the given subscriber's callback to a specific hook
	 * of the WordPress plugin API.
	 *
	 * @param Subscriber_Interface $subscriber The subscriber.
	 * @param string               $hook_name  The hook name.
	 * @param mixed                $parameters The hook parameters.
	 */
	private function remove_subscriber_callback( Subscriber_Interface $subscriber, $hook_name, $parameters ) {
		if ( is_string( $parameters ) ) {
			$this->remove_callback( $hook_name, array( $subscriber, $parameters ) );
		} elseif ( is_array( $parameters ) && isset( $parameters[0] ) ) {
			$this->remove_callback( $hook_name, array( $subscriber, $parameters[0] ), isset( $parameters[1] ) ? $parameters[1] : 10 );
		}
	}
}
