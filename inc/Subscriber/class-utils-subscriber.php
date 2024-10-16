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
 * Event subscriber that registers REST utils.
 *
 * @author Walger Marketing
 */
class Utils_Subscriber implements Subscriber_Interface {

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'rest_api_init' => 'register_routes',
		);
	}
	/**
	 * Register REST routes
	 *
	 * @return void
	 */
	public function register_routes() {
		register_rest_route(
			'CON/v1/utils',
			'/regenerate/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'regenerate_order_number' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'CON/v1/utils',
			'/reset/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'reset_order_number' ),
				'permission_callback' => '__return_true',
			)
		);
	}

	/**
	 * Regenerate order numbers
	 *
	 * @param  \WP_REST_Request $request The request.
	 * @return \WP_REST_Response
	 */
	public function regenerate_order_number( \WP_REST_Request $request ): \WP_REST_Response {
		$start_date = \WC_Admin_Settings::get_option( 'start_date' );
		$start_num  = \WC_Admin_Settings::get_option( 'start_num' );

		$orders = wc_get_orders(
			array(
				'numberposts'  => -1,
				'date_created' => '>=' . $start_date,
				'orderby'      => 'date_created',
				'order'        => 'ASC',
			)
		);

		foreach ( $orders as $order ) {
			$customer_order = new Customer_Order( $order->get_id() );
			$customer_order->set_order_num( (int) $start_num++ );
			$customer_order->save_meta();
		}

		return new \WP_REST_Response(
			array(
				'success' => true,
				'message' => 'Custom order numbers for ' . count( $orders ) . ' orders were succesfully regenerated.',
			)
		);
	}

	/**
	 * Reset order numbers
	 *
	 * @param  \WP_REST_Request $request The request.
	 * @return \WP_REST_Response
	 */
	public function reset_order_number( \WP_REST_Request $request ): \WP_REST_Response {
		$start_date = \WC_Admin_Settings::get_option( 'start_date' );
		$orders     = wc_get_orders(
			array(
				'numberposts'  => -1,
				'date_created' => '>=' . $start_date,
				'orderby'      => 'date_created',
				'order'        => 'ASC',
			)
		);

		foreach ( $orders as $order ) {
			$customer_order = new Customer_Order( $order->get_id() );
			$customer_order->reset_order_num();
		}

		return new \WP_REST_Response(
			array(
				'success' => true,
				'message' => 'Custom order numbers for ' . count( $orders ) . ' orders were succesfully reset.',
			)
		);
	}
}
