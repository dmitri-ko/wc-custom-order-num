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

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;


/**
 * Define Twig Template Controller
 *
 * Adds twig template support for plugin.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 */
class Twig_Controller {

	/**
	 * The TWIF environment
	 *
	 * @var Environment
	 */
	protected $twig;

	/**
	 * Create controller
	 *
	 * @param array $directories The array of directories to search templates.
	 */
	public function __construct( array $directories ) {
		$loader     = new FilesystemLoader( $directories );
		$this->twig = new Environment(
			$loader,
			array(
				'debug' => true,
			)
		);
		if ( current_user_can( 'administrator' ) ) {
			$this->twig->addExtension( new DebugExtension() );
		}

		/**
		 * Add gettext __ functions to twig functions.
		 */
		$function = new TwigFunction( '__', '__' );

		$this->twig->addFunction( $function );
	}

	/**
	 * Render template file
	 *
	 * @param string $template the template file.
	 * @param array  $params   template parameters.
	 *
	 * @return void
	 */
	public function render( string $template, array $params ) {
		$content = $this->twig->render( basename( $template ), $params );
		echo wp_kses_post( $content );
	}
}
