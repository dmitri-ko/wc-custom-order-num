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

namespace DKO\CON\Generator;

use DKO\CON\TemplateManagement\Templates;

/**
 * Dog DNA Metabox generator.
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class Template_Generator {

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	private $plugin_name;
	/**
	 * Plugin path.
	 *
	 * @var string
	 */
	private $plugin_path;

	/**
	 * Constructor.
	 *
	 * @param string $plugin_name Plugin name.
	 * @param string $plugin_path Plugin path.
	 */
	public function __construct( string $plugin_name, string $plugin_path ) {
		$this->plugin_name = $plugin_name;
		$this->plugin_path = $plugin_path;
	}


	/**
	 *  Generate the page content
	 *
	 * @param  string $template_name The name of the template.
	 * @param  array  $args The template arguments.
	 *
	 * @return string
	 */
	public function generate( string $template_name, array $args ): string {
		ob_start();

		$templates = new Templates( $this->plugin_name, $this->plugin_path );
		$templates->get_template(
			$template_name,
			$args
		);

		return ob_get_clean();
	}
}
