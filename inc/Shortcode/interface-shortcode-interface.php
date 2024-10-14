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
 * @subpackage CON/shortcode
 */

namespace DKO\CON\Shortcode;

/**
 * A shortcode represents the shortcode registered with the WordPress shortcode API.
 *
 * @author Walger Marketing
 */
interface Shortcode_Interface {

	/**
	 * Get the tag name used by the shortcode.
	 *
	 * @return string
	 */
	public static function get_name(): string;

	/**
	 * Handles the output of the shortcode.
	 *
	 * @param  array|string $attributes Shortcode attributes.
	 * @param  string       $content    Shortcode content.
	 * @return string
	 */
	public function handle( $attributes, string $content = '' ): string;
}
