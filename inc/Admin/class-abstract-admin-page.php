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
 * @subpackage CON/Admin
 */

namespace DKO\CON\Admin;

use DKO\CON\Generator\Template_Generator;
use DKO\CON\Options;

/**
 * A WordPress admin page.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
abstract class Abstract_Admin_Page {
	/**
	 * The plugin options.
	 *
	 * @var Options
	 */
	protected $options;

	/**
	 * Slug used by the admin page.
	 *
	 * @var string
	 */
	protected $slug = 'wc-custom-order-num';

	/**
	 * Path to the admin page templates.
	 *
	 * @var Template_Generator
	 */
	protected $generator;

	/**
	 * Constructor.
	 *
	 * @param Options            $options The options.
	 * @param Template_Generator $generator The Generator.
	 */
	public function __construct( Options $options, Template_Generator $generator ) {
		$this->options   = $options;
		$this->generator = $generator;
	}

	/**
	 * Get the title of the admin page in the WordPress admin menu.
	 *
	 * @return string
	 */
	public function get_menu_title() {
		return __( 'Woocommerce custom order number', 'wc-custom-order-num' );
	}

	/**
	 * Get the title of the admin page.
	 *
	 * @return string
	 */
	public function get_page_title() {
		return __( 'Woocommerce custom order number Settings', 'wc-custom-order-num' );
	}

	/**
	 * Get the URL of the admin page.
	 *
	 * @return string
	 */
	public function get_page_url() {
		return network_admin_url( $this->get_parent_slug() ) . '? page=' . $this->get_slug();
	}

	/**
	 * Get the title used for the admin page link on the plugins page.
	 *
	 * @return string
	 */
	public function get_plugins_page_title() {
		return __( 'Plugin page', 'wc-custom-order-num' );
	}

	/**
	 * Get the slug used by the admin page.
	 *
	 * @return string
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Render the plugin's admin page .
	 */
	public function render_page() {
		$page = $this;
		$this->render_template( 'admin-page.html', compact( 'page' ) );
	}

	/**
	 * Render the top section of the plugin's admin page.
	 */
	public function render_section() {
		$page = $this;
		$this->render_template( 'admin-section.html', compact( 'page' ) );
	}

	/**
	 * Get the capability required to view the admin page.
	 *
	 * @return string
	 */
	abstract public function get_capability();

	/**
	 * Get the parent slug of the admin page.
	 *
	 * @return string
	 */
	abstract public function get_parent_slug();

	/**
	 * Renders the given template if it's readable.
	 *
	 * @param string $template The template name.
	 * @param  array  $args The template arguments.
	 */
	protected function render_template( $template, array $args ) {
		echo $this->generator->generate( $template, $args );  // phpcs:ignore WordPress.Security.EscapeOutput
	}

	/**
	 * Get the URL used to submit the admin page form.
	 *
	 * @return string
	 */
	abstract public function get_form_url();

	/**
	 * Configure the admin page using the Settings API.
	 */
	abstract public function configure();
}
