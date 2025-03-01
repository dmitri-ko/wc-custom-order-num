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
use DKO\CON\Admin\WC_Settings_Page;
use DKO\CON\Entity\Customer_Order;

/**
 * WC settings page subscriber.
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class WC_Settings_Page_Subscriber implements Subscriber_Interface {
	/**
	 * The admin page.
	 *
	 * @var WC_Settings_Page
	 */
	protected $page;

	/**
	 * The basename of the plugin.
	 *
	 * @var string
	 */
	protected $plugin_basename;

	/**
	 * Slug used by the admin page.
	 *
	 * @var string
	 */
	protected static $slug = 'wc-custom-order-num';

	/**
	 * Constructor.
	 *
	 * @param WC_Settings_Page $page The admin page.
	 * @param string           $plugin_basename The plugin base name.
	 */
	public function __construct( WC_Settings_Page $page, $plugin_basename ) {
		$this->page            = $page;
		$this->plugin_basename = $plugin_basename;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'woocommerce_settings_tabs_array'          => array( 'configure', 99, 1 ),
			'woocommerce_settings_' . self::$slug      => 'render',
			'woocommerce_settings_save_' . self::$slug => 'save',
		);
	}

	/**
	 * Configure the admin page using the Settings API.
	 *
	 * @param array $settings_tabs The settings tabs.
	 */
	public function configure( $settings_tabs ) {
		return $this->page->configure( $settings_tabs );
	}

	/**
	 * Render page
	 *
	 * @return void
	 */
	public function render() {
		\WC_Admin_Settings::output_fields( $this->page->get_available_settings() );
	}

	/**
	 * Save settings
	 *
	 * @return void
	 */
	public function save() {
		// Get the settings fields.
		$settings = $this->page->get_available_settings();
		delete_transient( Customer_Order::CON_TRANSIENT_KEY );
		// Loop through settings to store historical values.
		foreach ( $settings as $setting ) {
			if ( ! isset( $setting['id'] ) ) {
				continue;
			}

			$option_key = $setting['id'];
			$old_value  = get_option( $option_key, '' );

			// Store historic values if changed.
			if ( ! empty( $old_value ) ) {
				$history_key = $option_key . '_history';
				$history     = get_option( $history_key, array() );

				if ( ! is_array( $history ) ) {
					$history = array();
				}

				// Add new history entry.
				$history[] = array(
					'timestamp' => time(),
					'value'     => $old_value,
				);

				update_option( $history_key, $history );
			}
		}
		\WC_Admin_Settings::save_fields( $this->page->get_available_settings() );
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public static function get_slug(): string {
		return self::$slug;
	}
}
