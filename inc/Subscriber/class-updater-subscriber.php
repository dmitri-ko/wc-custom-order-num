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
use DKO\CON\Updater\Updater_Interface;

/**
 * WC status page subscriber.
 *
 * @author Walger Marketing
 * @since 1.1
 */
class Updater_Subscriber implements Subscriber_Interface {


	/**
	 * The content generator.
	 *
	 * @var Updater_Interface
	 */
	protected $updater;


	/**
	 * Constructor.
	 *
	 * @param Updater_Interface $updater The plugin updater.
	 */
	public function __construct( Updater_Interface $updater ) {
		$this->updater = $updater;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'plugins_api'                   => array( 'info', 20, 3 ),
			'site_transient_update_plugins' => 'update',
			'upgrader_process_complete'     => array( 'upgrader_process_complete', 10, 2 ),
		);
	}

	/**
	 * Get plugin info the response for the current WordPress.org Plugin Installation API request.
	 *
	 * @param  false|object|array $result The result object or array. Default false.
	 * @param  string             $action The type of information being requested from the Plugin Installation API.
	 * @param  object             $args Plugin API arguments.
	 *
	 * @return false|object|array
	 */
	public function info( false|object|array $result, string $action, object $args ): false|object|array {
		return $this->updater->info( $result, $action, $args );
	}

	/**
	 * Update transient
	 *
	 * @param  mixed $transient WP transient.
	 *
	 * @return mixed
	 */
	public function update( mixed $transient ): mixed {
		return $this->updater->update( $transient );
	}

	/**
	 * Purge data when the upgrader process is complete
	 *
	 * @param  \WP_Upgrader $upgrader WP_Upgrader instance.
	 * @param  array        $hook_extra Array of bulk item update data.
	 *                      action string
	 *                             Type of action. Default 'update'.
	 *                      type string
	 *                             Type of update process. Accepts 'plugin', 'theme', 'translation', or 'core'.
	 *                      bulk bool
	 *                             Whether the update process is a bulk update. Default true.
	 *                      plugins array
	 *                             Array of the basename paths of the plugins’ main files.
	 *                      themes array
	 *                             The theme slugs.
	 *                      translations array
	 *                             Array of translations update data.
	 *                          language string
	 *                                The locale the translation is for.
	 *                           type string
	 *                                Type of translation. Accepts 'plugin', 'theme', or 'core'.
	 *                           slug string
	 *                                Text domain the translation is for. The slug of a theme/plugin or 'default' for core translations.
	 *                       version string
	 *                             The version of a theme, plugin, or core.
	 *
	 * @return void
	 */
	public function purge( \WP_Upgrader $upgrader, array $hook_extra ) {
		$this->updater->purge( $upgrader, $hook_extra );
	}
}
