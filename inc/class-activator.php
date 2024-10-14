<?php
/**
 * This file is part of the Woocommerce Custom Order Number plugin.
 *
 * (c) Walger Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Walger Marketing
 * @package CON
 */

namespace DKO\CON;

/**
 * Fired during plugin activation
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @link https://www.walger-marketing.de/dko-events
 *
 * @author Walger Marketing
 **/
class Activator {

	/**
	 * Minimum PHP version of the plugin
	 *
	 * @var string
	 */
	private $min_php;

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * Constructor
	 *
	 * @param string $plugin_name The plugin name.
	 * @param string $min_php     The minimum PHP version.
	 */
	public function __construct( string $plugin_name, string $min_php ) {
		$this->plugin_name = $plugin_name;
		$this->min_php     = $min_php;
	}

	/**
	 * Activate plugin
	 *
	 * Hook all necessary actions during plugin activation.
	 */
	public function activate() {

		// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
		if ( version_compare( PHP_VERSION, $this->min_php, '<' ) ) {
			deactivate_plugins( $this->plugin_name );
			wp_die( esc_html( 'This plugin requires a minmum PHP Version of ' . self::$min_php ) );
		}
		flush_rewrite_rules();
	}
}
