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
 * @subpackage CON/AssetManagement
 */

namespace DKO\CON\AssetManagement;

/**
 * Define the assets functionality
 *
 * Adds assets file support for plugin.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 */
class Asset_Meta {

	/**
	 * The path to the asset
	 *
	 * @var string
	 */
	private $asset_file_path;

	/**
	 * The assets
	 *
	 * @var array
	 */
	private $assets;

	/**
	 * Create assets
	 *
	 * @param string $asset_file_path the psth to the asset.
	 */
	public function __construct( $asset_file_path ) {
		$this->asset_file_path = $asset_file_path;
		$this->init();
	}

	/**
	 * Initialize assets
	 *
	 * @return void
	 * @throws \Error The assets file doesn't exist.
	 */
	private function init() {
		$this->assets = array();
		if ( ! file_exists( $this->asset_file_path ) ) {
			throw new \Error( esc_html__( 'Asset file is not found.', 'dko-events' ) );
		}
		$this->assets = include $this->asset_file_path;
	}

	/**
	 * Get assets
	 *
	 * @return array
	 */
	public function get_assets() {
		return $this->assets;
	}
}
