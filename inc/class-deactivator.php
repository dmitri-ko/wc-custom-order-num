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
 * Fired during plugin deactivation
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 **/
class Deactivator {


	/**
	 * Deactivate plugin.
	 *
	 * Hook actions needed during plugin deactivation.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
	}
}
