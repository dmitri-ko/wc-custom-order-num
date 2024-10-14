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
			'texts'    => array(
				'title'    => __( 'Report Template Texts', 'dog-dna-tests' ),
				'slug'     => $this->get_slug() . '-texts',
				'template' => 'admin-section-texts.html',
				'fields'   => array(
					'salutation_text'        => '',
					'found_breed_text'       => '',
					'cover_letter_text'      => '',
					'test_note_text'         => '',
					'mixed_explanation_text' => '',
					'test_explanation_text'  => '',
				),
			),
			'advanced' => array(
				'title'    => __( 'Advanced', 'dog-dna-tests' ),
				'slug'     => $this->get_slug() . '-advanced',
				'template' => 'admin-section-advanced.html',
				'fields'   => array(
					'gotenberg_url'            => 'http://localhost:3000',
					'gotenberg_default_user'   => 'CON_admin',
					'gotenberg_http_auth_user' => '',
				),
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
	 * Render the texts section.
	 */
	public function render_section_texts() {
		$page = $this;
		$this->render_template( $this->tabs['texts']['template'], compact( 'page' ) );
	}

	/**
	 * Render the advanced section.
	 */
	public function render_section_advanced() {
		$page = $this;
		$this->render_template( $this->tabs['advanced']['template'], compact( 'page' ) );
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
