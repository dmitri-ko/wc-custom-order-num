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
 * Define the assets paths functionality
 *
 * Make paths to different plugin resources.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 */
class Asset_Path {

	/**
	 * The resource name
	 *
	 * @var string
	 */
	private $name;
	/**
	 * The asset base directory name
	 *
	 * @var string
	 */
	private $base_dir_name;
	/**
	 * Path to asset root
	 *
	 * @var string
	 */
	private $path_to_asset;
	/**
	 * URLto asset root
	 *
	 * @var string
	 */
	private $url_to_asset;


	/**
	 * Create path maker
	 *
	 * @param string $name          The asset name.
	 * @param string $base_dir_name The asset base directoryname.
	 * @param string $path_to_asset The asset root path.
	 * @param string $url_to_asset  The asset root URL.
	 */
	public function __construct( string $name = 'index', string $base_dir_name = 'assets', string $path_to_asset = '', string $url_to_asset = '' ) {
		$this->name          = $name;
		$this->base_dir_name = $base_dir_name;
		$this->path_to_asset = $path_to_asset;
		$this->url_to_asset  = $url_to_asset;
	}

	/**
	 * Get asset file name with full path
	 *
	 * @param string $type        the asset type.
	 * @param bool   $is_minified if the asset is minified.
	 *
	 * @return string
	 */
	public function get_full_filename( string $type, bool $is_minified = false ): string {
		return trailingslashit( $this->path_to_asset ) . $this->get_asset_dir( $type ) . $this->get_asset_filename( $type, $is_minified );
	}

	/**
	 * Get asset file name with full URL
	 *
	 * @param string $type        the asset type.
	 * @param bool   $is_minified if the asset is minified.
	 *
	 * @return string
	 */
	public function get_url( $type, $is_minified = false ) {
		return trailingslashit( $this->url_to_asset ) . $this->get_asset_dir( $type ) . $this->get_asset_filename( $type, $is_minified );
	}

	/**
	 * Check if the asset exists
	 *
	 * @param  string $type        the asset type.
	 * @param  bool   $is_minified if the asset is minified.
	 * @return bool
	 */
	public function exists( $type, $is_minified = false ): bool {
		return file_exists( $this->get_full_filename( $type, $is_minified ) );
	}

	/**
	 * Get asset file name
	 *
	 * @param string $type        the asset type.
	 * @param bool   $is_minified if the asset is minified.
	 *
	 * @return string
	 */
	protected function get_asset_filename( string $type, bool $is_minified ): string {
		return $this->name . $this->get_asset_suffix( $type, $is_minified ) . '.' . $type;
	}

	/**
	 * Get asset suffix
	 *
	 * @param string $type        the asset type.
	 * @param bool   $is_minified if the asset is minified.
	 *
	 * @return string
	 */
	protected function get_asset_suffix( string $type, bool $is_minified ): string {
		return ( $is_minified ? '.min' : '' ) . ( 'php' === $type ? '.asset' : '' );
	}

	/**
	 * Get asset directory
	 *
	 * @param  string $type the asset type.
	 * @return string
	 */
	protected function get_asset_dir( string $type ): string {
		$subdirs = array(
			'php' => '/scripts',
			'js'  => '/scripts',
			'css' => '/css',
			'jpg' => '/img',
		);
		return trailingslashit( $this->base_dir_name . ( isset( $subdirs[ $type ] ) ? $subdirs[ $type ] : '' ) );
	}
}
