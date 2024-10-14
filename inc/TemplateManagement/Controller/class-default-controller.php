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
 * Define Default Template Controller
 *
 * Adds template support for plugin.
 *
 * @author Walger Marketing
 */
class Default_Controller {

	/**
	 * Render template file
	 *
	 * @param string $template the template file.
	 * @param array  $params   template parameters.
	 *
	 * @return void
	 */
	public function render( string $template, array $params ) {

		if ( is_array( $params ) && isset( $params ) ) :
			extract( $params );
		endif;

		include $template;
	}
}
