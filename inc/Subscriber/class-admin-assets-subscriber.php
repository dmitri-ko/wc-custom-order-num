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

use DKO\CON\AssetManagement\Asset_Meta;
use DKO\CON\AssetManagement\Asset_Path;
use DKO\CON\EventManagement\Subscriber_Interface;

/**
 * Event subscriber that registers assets with WordPress.
 *
 * @author Walger Marketing
 */
class Admin_Assets_Subscriber implements Subscriber_Interface {



	/**
	 * Path to the style.
	 *
	 * @var Asset_Path
	 */
	private $style_path;

	/**
	 * Path to the script.
	 *
	 * @var Asset_Path
	 */
	private $script_path;

	/**
	 * The language path for translations.
	 *
	 * @var string
	 */
	private $lang_path;

	/**
	 * The asset name.
	 *
	 * @var string
	 */
	private $asset_name;

	/**
	 * The asset version
	 *
	 * @var string
	 */
	private $asset_version;

	/**
	 * Constructor
	 *
	 * @param Asset_Path $style_path    Path to the style..
	 * @param Asset_Path $script_path   Path to the script.
	 * @param string     $asset_name    The asset name.
	 * @param string     $asset_version The asset version.
	 * @param string     $lang_path     The language path for translations.
	 */
	public function __construct( Asset_Path $style_path, Asset_Path $script_path, $asset_name, $asset_version, $lang_path ) {
		$this->style_path    = $style_path;
		$this->script_path   = $script_path;
		$this->asset_name    = $asset_name;
		$this->asset_version = $asset_version;
		$this->lang_path     = $lang_path;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'admin_enqueue_scripts' => 'add_admin_assets',
		);
	}

	/**
	 * Add assets for the WordPress Admin UI.
	 */
	public function add_admin_assets() {

		if ( $this->style_path->exists( 'css', true ) ) {
			wp_enqueue_style(
				$this->asset_name . '-admin',
				$this->style_path->get_url( 'css', true ),
				array(),
				$this->asset_version,
				'all'
			);
		}
		if ( $this->script_path->exists( 'js', true ) ) {
			try {
				$assets = ( new Asset_Meta( $this->script_path->get_full_filename( 'php', true ) ) )->get_assets();
			} catch ( \Error $e ) {
				$assets = array(
					'dependencies' => array( 'wp-plugins', 'wp-edit-post', 'wp-element', 'wp-i18n' ),
					'version'      => $this->asset_version,
				);
			}
			$assets['dependencies'] = array_merge( $assets['dependencies'], array( 'wp-api' ) );
			wp_enqueue_script(
				$this->asset_name . '-admin',
				$this->script_path->get_url( 'js', true ),
				$assets['dependencies'],
				$assets['version'],
				true
			);

			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations(
					$this->asset_name . '-admin',
					'dko-events',
					$this->lang_path
				);
			}
		}
	}
}
