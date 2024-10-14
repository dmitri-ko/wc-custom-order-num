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

namespace DKO\CON\Subscriber;

use DKO\CON\EventManagement\Subscriber_Interface;
use DKO\CON\Admin\Abstract_Admin_Page;

/**
 * Admin page subscriber that registers plugin settings page in Admin UI with WordPress.
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class Admin_Page_Subscriber implements Subscriber_Interface {
	/**
	 * The admin page.
	 *
	 * @var AbstractAdminPage
	 */
	protected $page;

	/**
	 * The basename of the plugin.
	 *
	 * @var string
	 */
	protected $plugin_basename;

	/**
	 * Constructor.
	 *
	 * @param Abstract_Admin_Page $page The admin page.
	 * @param string              $plugin_basename The plugin base name.
	 */
	public function __construct( Abstract_Admin_Page $page, $plugin_basename ) {
		$this->page            = $page;
		$this->plugin_basename = $plugin_basename;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'admin_init'          => 'configure',
			'admin_menu'          => 'add_admin_page',
			'plugin_action_links' => array( 'add_plugin_page_link', 10, 2 ),
		);
	}

	/**
	 * Adds the plugin's admin page to the options menu.
	 */
	public function add_admin_page() {
		add_submenu_page( $this->page->get_parent_slug(), $this->page->get_page_title(), $this->page->get_menu_title(), $this->page->get_capability(), $this->page->get_slug(), array( $this->page, 'render_page' ) );
	}

	/**
	 * Adds link from plugins page to Passwords Evolved admin page.
	 *
	 * @param array  $links The links.
	 * @param string $file The file.
	 *
	 * @return array
	 */
	public function add_plugin_page_link( array $links, $file ) {
		if ( $file !== $this->plugin_basename ) {
			return $links;
		}

		array_unshift( $links, sprintf( '<a href="%s">%s</a>', $this->page->get_page_url(), $this->page->get_plugins_page_title() ) );

		return $links;
	}

	/**
	 * Configure the admin page using the Settings API.
	 */
	public function configure() {
		$this->page->configure();
	}
}
