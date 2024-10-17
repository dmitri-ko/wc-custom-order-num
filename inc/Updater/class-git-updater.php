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
 * @subpackage CON/updater
 */

namespace DKO\CON\Updater;

/**
 * Plugin updater
 *
 * @author Walger Marketing
 */
class Git_Updater implements Updater_Interface {

	const CACHE_KEY = 'wc-custom-order-num-updater';

	/**
	 * Is use cache
	 *
	 * @var bool
	 */
	protected $cache_allowed;

	/**
	 * Plugin name.
	 *
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $plugin_version;

	/**
	 * Default constructor
	 *
	 * @param string $plugin_name Plugin name.
	 * @param string $plugin_version Plugin version.
	 * @param  bool   $with_cache Use cache flag.
	 */
	public function __construct( string $plugin_name, string $plugin_version, bool $with_cache = false ) {
		$this->plugin_name    = $plugin_name;
		$this->plugin_version = $plugin_version;
		$this->cache_allowed  = $with_cache;
	}

	/**
	 * Request info.json
	 *
	 * @return mixed
	 */
	public function request(): mixed {

		$remote = get_transient( self::CACHE_KEY );

		if ( false === $remote || ! $this->cache_allowed ) {

			$remote = wp_remote_get(
				'https://github.com/dmitri-ko/wc-custom-order-num/releases/download/initial/info.json',
				array(
					'timeout' => 10,
					'headers' => array(
						'Accept' => 'application/json',
					),
				)
			);

			if ( is_wp_error( $remote ) || 200 !== wp_remote_retrieve_response_code( $remote ) || empty( wp_remote_retrieve_body( $remote ) ) ) {
				return false;
			}

			set_transient( self::CACHE_KEY, $remote, DAY_IN_SECONDS );

		}

		$remote = json_decode( wp_remote_retrieve_body( $remote ) );

		return $remote;
	}

	/**
	 * Get plugin info the result for the current WordPress.org Plugin Installation API request.
	 *
	 * @param  false|object|array $result The result object or array. Default false.
	 * @param  string             $action The type of information being requested from the Plugin Installation API.
	 * @param  object             $args Plugin API arguments.
	 *
	 * @return false|object|array
	 */
	public function info( false|object|array $result, string $action, object $args ): false|object|array {
		if ( 'plugin_information' !== $action ) {
			return $result;
		}

		if ( empty( $args->slug ) || $this->plugin_name !== $args->slug ) {
			return $result;
		}

		$remote = $this->request();

		if ( ! $remote ) {
			return $result;
		}

		$result = new \stdClass();

		$result->name           = $remote->name;
		$result->slug           = $remote->slug;
		$result->version        = $remote->version;
		$result->tested         = $remote->tested;
		$result->requires       = $remote->requires;
		$result->author         = $remote->author;
		$result->author_profile = $remote->author_profile;
		$result->donate_link    = $remote->donate_link;
		$result->homepage       = $remote->homepage;
		$result->download_link  = $remote->download_url;
		$result->trunk          = $remote->download_url;
		$result->requires_php   = $remote->requires_php;
		$result->last_updated   = $remote->last_updated;

		$result->sections = array(
			'description'  => $remote->sections->description,
			'installation' => $remote->sections->installation,
			'changelog'    => $remote->sections->changelog,
		);

		if ( ! empty( $remote->banners ) ) {
			$result->banners = array(
				'low'  => $remote->banners->low,
				'high' => $remote->banners->high,
			);
		}

		return $result;
	}

	/**
	 * Update transient
	 *
	 * @param  mixed $transient WP transient.
	 *
	 * @return mixed
	 */
	public function update( mixed $transient ): mixed {
		if ( empty( $transient->checked ) ) {
			return $transient;
		}

		$remote = $this->request();

		if ( $remote && version_compare( $this->plugin_version, $remote->version, '<' ) && version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' ) && version_compare( $remote->requires_php, PHP_VERSION, '<' ) ) {
			$response              = new \stdClass();
			$response->slug        = $this->plugin_name;
			$response->plugin      = "{$this->plugin_name}/{$this->plugin_name}.php";
			$response->new_version = $remote->version;
			$response->tested      = $remote->tested;
			$response->package     = $remote->download_url;

			$transient->response[ $response->plugin ] = $response;

		}

		return $transient;
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
		if ( $this->cache_allowed && 'update' === $hook_extra['action'] && 'plugin' === $hook_extra['type'] ) {
			delete_transient( self::CACHE_KEY );
		}
	}
}
