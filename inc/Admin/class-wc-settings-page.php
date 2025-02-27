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
 * @subpackage CON/Admin
 */

namespace DKO\CON\Admin;

use DKO\CON\Entity\Setting;

/**
 * The plugin's WC settings page.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class WC_Settings_Page {
	/**
	 * Available settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Slug used by the admin page.
	 *
	 * @var string
	 */
	protected $slug;

	/**
	 * Constructor.
	 *
	 * @param string $slug Page slug.
	 */
	public function __construct( string $slug ) {
		$this->slug     = $slug;
		$this->settings = array(
			new Setting( 'start_date', __( 'Start date', 'wc-custom-order-num' ), gmdate( get_option( 'date_format' ) ), 'date' ),
			new Setting( 'start_num', __( 'Starting number', 'wc-custom-order-num' ), 1 ),
			new Setting( 'num_length', __( 'Number length', 'wc-custom-order-num' ), 4 ),
			new Setting( 'prefix', __( 'Prefix', 'wc-custom-order-num' ) ),
			new Setting( 'postfix', __( 'Postfix', 'wc-custom-order-num' ) ),
			new Setting( 'is_cache_allowed', __( 'Cache', 'wc-custom-order-num' ), false, 'checkbox' ),
		);
	}

	/**
	 * Add custom tab
	 *
	 * @param  array $settings_tabs The Woocommerce settings tabs.
	 *
	 * @return array
	 */
	public function configure( array $settings_tabs ): array {
		$settings_tabs[ $this->get_slug() ] = __( 'Custom order numbers', 'wc-custom-order-num' );
		return $settings_tabs;
	}

	/**
	 * Get available settings
	 *
	 * @return array
	 */
	public function get_available_settings(): array {
		$wc_settings = array(
			array(
				'title' => __( 'Woocommerce custom number settings', 'wc-custom-order-num' ),
				'desc'  => __( 'Manage your custom number settings for the WooCommerce Custom Order Number plugin.', 'wc-custom-order-num' ),
				'type'  => 'title',
				'id'    => 'woocommerce_redirects_license_settings',
			),
		);
		foreach ( $this->settings as $setting ) {
			$wc_settings[] =
			array(
				'name'    => $setting->get_name(),
				'title'   => $setting->get_description(),
				'id'      => $setting->get_name(),
				'default' => $setting->get_default(),
				'type'    => $setting->get_widget(),
			);
		}
		$wc_settings[] = array(
			'type' => 'sectionend',
			'id'   => $this->get_slug() . '_settings',
		);
		return $wc_settings;
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function get_slug(): string {
		return $this->slug;
	}
}
