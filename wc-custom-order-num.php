<?php
/**
 * Woocommerce Custom Order Number
 *
 * @package           CON
 * @author            Walger Marketing
 * @copyright         2024 Walger Marketing
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Custom Order Number
 * Plugin URI:        https://www.walger-marketing.de/wc-custom-order-num
 * Description:       Add custom order number functionality.
 * Version:           1.1.5
 * Requires at least: 5.6
 * Requires PHP:      8.0
 * Author:            Walger Marketing
 * Author URI:        https://www.walger-marketing.de
 * Text Domain:       wc-custom-order-num
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Update URI:        https://www.walger-marketing.de/wc-custom-order-num
 * Requires Plugins:  woocommerce
 */

use DKO\CON\Plugin;
use DKO\CON\Activator;
use DKO\CON\Deactivator;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	exit;
}

/**
 * Autoload Classes
 */
$vendor_file = plugin_dir_path( __FILE__ ) . '/vendor/autoload.php';

if ( is_readable( $vendor_file ) ) {
	include_once $vendor_file;
}

$min_php = '8.0.0';

/**
* Register Activation and Deactivation Hooks
* This action is documented in inc/core/class-surface.php
*/
$plugin_activator = new Activator( Plugin::NAME, $min_php );
register_activation_hook( __FILE__, array( $plugin_activator, 'activate' ) );

/**
* The code that runs during plugin deactivation.
* This action is documented inc/core/class-deactivator.php
*/

register_deactivation_hook( __FILE__, array( Deactivator::class, 'deactivate' ) );

/**
 * Load plugin text domain for translations.
 *
 * @return void
 */
function wc_custom_order_num_domain() {
	load_plugin_textdomain( Plugin::NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

// Check the minimum required PHP version and run the plugin.
if ( version_compare( PHP_VERSION, $min_php, '>=' ) ) {
	// Load Dog DNA tests.
	$con_plugin = new Plugin( __FILE__ );
	add_action( 'plugins_loaded', array( $con_plugin, 'load' ) );
	add_action( 'plugins_loaded', 'wc_custom_order_num_domain' );
}
