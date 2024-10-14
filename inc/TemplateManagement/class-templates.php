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
 * @subpackage CON/templatemanagement
 */

namespace DKO\CON\TemplateManagement;

use DKO\CON\TemplateManagement\Controller\Controller_Factory;

/**
 * Define the templating functionality
 *
 * Adds support for plugin templates and allow to overwrite them in themes.
 *
 * @author Walger Marketing
 */
class Templates {

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
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/theme/dko-events-templates/$template_name
	 * 2. /themes/theme/$template_name
	 * 3. /plugins/dko-events/templates/$template_name.
	 *
	 * @param string $template_name Template to load.
	 * @param string $template_path Asset_Path to templates.
	 * @param string $default_path  Default path to template files.
	 *
	 * @return string Asset_Path to the template file.
	 * @since  1.0.0
	 */
	public function locate_template( string $template_name, string $template_path = '', string $default_path = '' ): string {
		if ( ! $template_path ) :
			$template_path = $this->plugin_name . '-templates/';
		endif;

		if ( ! $default_path ) :
			$default_path = trailingslashit( $this->plugin_path ) . 'templates/';
		endif;

		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name,
			)
		);

		if ( ! $template ) :
			$template = $default_path . $template_name;
		endif;

		return apply_filters( 'dko_locate_template', $template, $template_name, $template_path, $default_path );
	}

	/**
	 * Get template.
	 *
	 * Search for the template and include the file.
	 *
	 * @param string $template_name Template to load.
	 * @param array  $args          Args passed for the template file.
	 * @param string $template_path Asset_Path to templates.
	 * @param string $default_path  Default path to template files.
	 *
	 * @since 1.0.0
	 *
	 * @see locate_template()
	 */
	public function get_template( string $template_name, array $args = array(), string $template_path = '', string $default_path = '' ) {
		$supported_extensions = array( 'twig', 'php' );
		$templates_stack      = array();
		foreach ( $supported_extensions  as $extension ) {
			$template_filename = $template_name . '.' . $extension;
			$template_file     = $this->locate_template( $template_filename, $template_path, $default_path );
			if ( file_exists( $template_file ) ) {
				$templates_stack[ $extension ] = $template_file;
			}
		}

		if ( isset( $templates_stack['twig'] ) && ( false !== strpos( $templates_stack['twig'], $this->plugin_name . '-templates/' ) ) ) {
			$template_file = $templates_stack['twig'];
			$extension     = 'twig';
		} elseif ( isset( $templates_stack['php'] ) && ( false !== strpos( $templates_stack['php'], $this->plugin_name . '-templates/' ) ) ) {
			$template_file = $templates_stack['php'];
			$extension     = 'php';
		} elseif ( isset( $templates_stack['twig'] ) ) {
			$template_file = $templates_stack['twig'];
			$extension     = 'twig';
		} elseif ( isset( $templates_stack['php'] ) ) {
			$template_file = $templates_stack['php'];
			$extension     = 'php';
		} else {
			$template_file = '';
		}

		if ( ! empty( $template_file ) ) {
			$template = Controller_Factory::get_controller( array( dirname( $template_file ) ), $extension );
			$template->render( $template_file, $args );
		}
		// TODO: Implement throw Error.
	}
}
