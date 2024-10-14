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
 * The plugin's admin page.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class Admin_Page extends Abstract_Admin_Page {
	/**
	 * Admin page tabs
	 *
	 * @var array
	 */
	private $tabs;

	/**
	 * {@inheritdoc}
	 */
	public function __construct( Options $options, Template_Generator $generator ) {
		parent::__construct( $options, $generator );
		$this->tabs = array(
			'common' => array(
				'title'    => __( 'Main options', 'wc-custom-order-num' ),
				'slug'     => $this->get_slug() . '-common',
				'template' => 'admin-section-common.html',
				'fields'   => array(
					'start_date' => gmdate( get_option( 'date_format' ) ),
					'prefix'     => '',
					'start_num'  => '0001',
					'postfix'    => '',
				),
			),
			'utils'  => array(
				'title'    => __( 'Utils', 'wc-custom-order-num' ),
				'slug'     => $this->get_slug() . '-utils',
				'template' => 'admin-section-utils.html',
				'fields'   => array(),
			),
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_options() {
		return $this->options;
	}
	/**
	 * {@inheritdoc}
	 */
	public function get_capability() {
		return 'edit_users';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_parent_slug() {
		return 'options-general.php';
	}

	/**
	 * {@inheritdoc}
	 */
	public function get_form_url() {
		return 'options.php';
	}

	/**
	 *  {@inheritDoc}
	 */
	public function configure() {
		foreach ( $this->tabs as $tab => $tab_data ) {
			foreach ( $tab_data['fields'] as $field => $default ) {
				register_setting( $tab_data['slug'], $this->options->get_option_name( $field ) );
			}

			add_settings_section(
				$tab_data['slug'],
				__( 'Settings' ) . ' › ' . $tab_data['title'],
				array( $this, 'render_section_' . $tab ),
				$tab_data['slug']
			);
		}
	}

	/**
	 * Render the common section.
	 */
	public function render_section_common() {
		$page = $this;
		$this->render_template( $this->tabs['common']['template'], compact( 'page' ) );
	}

	/**
	 * Render the utils section.
	 */
	public function render_section_utils() {
		$page = $this;
		$this->render_template( $this->tabs['utils']['template'], compact( 'page' ) );
	}

	/**
	 * Get the tabs
	 *
	 * @return array
	 */
	public function get_tabs() {
		return $this->tabs;
	}
}
