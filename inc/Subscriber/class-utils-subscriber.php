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

		register_rest_route(
			'CON/v1/utils',
			'/fill-gaps/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'fill_gaps_order_number' ),
				'permission_callback' => '__return_true',
			)
		);

		register_rest_route(
			'CON/v1/utils',
			'/persist-numbers/',
			array(
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'persist_order_num' ),
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
			$customer_order = new Customer_Order( $order );
			$customer_order->set_order_num_seed( (int) $start_num++ );
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
			$customer_order = new Customer_Order( $order );
			$customer_order->reset_order_num();
		}

		return new \WP_REST_Response(
			array(
				'success' => true,
				'message' => 'Custom order numbers for ' . count( $orders ) . ' orders were succesfully reset.',
			)
		);
	}

	/**
	 * Fill gaps in order numbers
	 *
	 * @param  \WP_REST_Request $request The request.
	 * @return \WP_REST_Response
	 */
	public function fill_gaps_order_number( \WP_REST_Request $request ): \WP_REST_Response {
		$start_date = \WC_Admin_Settings::get_option( 'start_date' );

		$orders = wc_get_orders(
			array(
				'numberposts'  => -1,
				'date_created' => '>=' . $start_date,
				'orderby'      => 'date_created',
				'order'        => 'ASC',
			)
		);

		$current_num = -1;
		foreach ( $orders as $order_key => $order ) {
			$customer_order = new Customer_Order( $order );
			if ( ( -1 === $current_num ) && ! ( $order->get_meta( Customer_Order::CON_META_KEY_ORDER_NUM_SEED ) ) ) {
				$current_num = $orders[ $order_key - 1 ]->get_meta( Customer_Order::CON_META_KEY_ORDER_NUM_SEED );
				++$current_num;
			}
			if ( -1 !== $current_num ) {
				$customer_order->set_order_num_seed( (int) $current_num );
				$customer_order->save_meta();
				set_transient( Customer_Order::CON_TRANSIENT_KEY, $current_num, DAY_IN_SECONDS );
				++$current_num;
			}
		}

		return new \WP_REST_Response(
			array(
				'success' => true,
				'message' => 'Custom order numbers for ' . count( $orders ) . ' orders were succesfully fixed.',
			)
		);
	}

	/**
	 * Persist order numbers based on historical settings.
	 *
	 * @param  \WP_REST_Request $request The request.
	 * @return \WP_REST_Response
	 */
	public function persist_order_num( \WP_REST_Request $request ): \WP_REST_Response {

		// Process all orders newer than the latest start_date using the current settings.
		$orders           = wc_get_orders(
			array(
				'limit'        => -1, // Get all matching orders.
				'type'         => 'shop_order',
				'meta_key'     => Customer_Order::CON_META_KEY_ORDER_NUM_SEED,
				'meta_compare' => 'EXISTS',
				'orderby'      => 'date_created',
				'order'        => 'ASC',
			)
		);
		$processed_orders = 0;
		foreach ( $orders as $order ) {
			try {
				$customer_order = new Customer_Order( $order );

				// Fetch order number seed.
				$customer_order->get_order_num_seed();

				// Set new order number.
				$customer_order->set_order_number( $customer_order->construct_order_num() );
				$customer_order->save_meta();

				++$processed_orders;

			} catch ( \Exception ) {
				continue; // Skip problematic orders.
			}
		}

		return new \WP_REST_Response(
			array(
				'success' => true,
				'message' => 'Custom order numbers for ' . $processed_orders . ' orders were successfully fixed.',
			)
		);
	}
}
