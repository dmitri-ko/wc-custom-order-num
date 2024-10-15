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

use DKO\CON\EventManagement\Generator_Interface;
use DKO\CON\TemplateManagement\Templates;

/**
 * Dog DNA Metabox generator.
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class Template_Generator implements Generator_Interface {

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
	 * Template name.
	 *
	 * @var string
	 */
	private $template_name;


	/**
	 * Constructor.
	 *
	 * @param string $plugin_name Plugin name.
	 * @param string $plugin_path Plugin path.
	 * @param string $template_name Template name.
	 */
	public function __construct( string $plugin_name, string $plugin_path, string $template_name = '' ) {
		$this->plugin_name   = $plugin_name;
		$this->plugin_path   = $plugin_path;
		$this->template_name = $template_name;
	}

	/**
	 * Set template name
	 *
	 * @param  string $template_name New template name.
	 *
	 * @return void
	 */
	public function set_template_name( string $template_name ) {
		$this->template_name = $template_name;
	}

	/**
	 * Get tmplate name
	 *
	 * @return string
	 */
	public function get_template_name(): string {
		return $this->template_name;
	}

	/**
	 *  Generate content
	 *
	 * @param  array $args The template arguments.
	 *
	 * @return string
	 */
	public function generate( array $args ): string {
		if ( empty( $this->get_template_name() ) ) {
			return '';
		}

		ob_start();

		$templates = new Templates( $this->plugin_name, $this->plugin_path );
		$templates->get_template(
			$this->template_name,
			$args
		);

		return ob_get_clean();
	}
}
