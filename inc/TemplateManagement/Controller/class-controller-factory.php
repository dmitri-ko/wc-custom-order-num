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
 * @subpackage CON/templates
 */

namespace DKO\CON\TemplateManagement\Controller;

/**
 * Define Twig Template Controller
 *
 * Adds twig template support for plugin.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 */
class Controller_Factory {

	/**
	 * Get template controller
	 *
	 * @param  array  $template_paths The directories for templates.
	 * @param  string $template_type  Template type.
	 * @return Template_Controller
	 */
	public static function get_controller( array $template_paths, string $template_type ) {
		switch ( $template_type ) {
			case 'twig':
				return new Twig_Controller( $template_paths );
			default:
				return new Default_Controller();
		}
	}
}
