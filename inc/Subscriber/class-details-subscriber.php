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
 * @subpackage CON/subscriber
 */

namespace DKO\CON\Subscriber;

use DKO\CON\EventManagement\Subscriber_Interface;
use DKO\CON\Service\Plugin_Details;

/**
 * Plugin details subscriber.
 *
 * @author Walger Marketing
 * @since 1.1
 */
class Details_Subscriber implements Subscriber_Interface {


	/**
	 * The plugin details.
	 *
	 * @var Plugin_Details
	 */
	protected $info;


	/**
	 * Constructor.
	 *
	 * @param Plugin_Details $info The plugin info.
	 */
	public function __construct( Plugin_Details $info ) {
		$this->info = $info;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'plugin_action_links' => array( 'show_details', 10, 3 ),
			'plugin_row_meta'     => array( 'show_details', 10, 3 ),

		);
	}

	/**
	 * Add a "details" link to open a thickbox popup with information about
	 * the plugin from the public directory.
	 *
	 * @since 1.1.1
	 *
	 * @param array  $links List of links.
	 * @param string $plugin_file Relative path to the main plugin file from the plugins directory.
	 * @param array  $plugin_data Data from the plugin headers.
	 * @return array
	 */
	public function show_details( array $links, string $plugin_file, array $plugin_data ): array {
		return $this->info->plugin_links( $links, $plugin_file, $plugin_data );
	}
}
