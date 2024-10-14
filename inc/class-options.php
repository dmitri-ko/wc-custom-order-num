<?php
/**
 * This file is part of the Woocommerce Custom Order Number plugin.
 *
 * (c) Walger Marketing
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Walger Marketing
 * @package CON
 */

namespace DKO\CON;

/**
 * Manages the plugin's options using the options API.
 *
 * @link https://www.walger-marketing.de
 *
 * @author Walger Marketing
 */
class Options {

	/**
	 * Get the option name used to store the option in the WordPress database.
	 *
	 * @param string $name The option name.
	 *
	 * @return string
	 */
	public function get_option_name( $name ) {
		return 'con_' . $name;
	}

	/**
	 * Gets the option for the given name. Returns the default value if the value does not exist.
	 *
	 * @param string $name The option name.
	 * @param mixed  $default Defasult value.
	 *
	 * @return mixed
	 */
	public function get( $name, $default = null ) {
		$option = get_network_option( null, $this->get_option_name( $name ), $default );

		// Smart-cast the option as an array.
		if ( is_array( $default ) && ! is_array( $option ) ) {
			$option = (array) $option;
		}

		return $option;
	}

	/**
	 * Checks if the option with the given name exists or not.
	 *
	 * @param string $name The option name.
	 *
	 * @return bool
	 */
	public function has( $name ) {
		return null !== $this->get( $name );
	}

	/**
	 * Removes the option with the given name.
	 *
	 * @param string $name The option name.
	 */
	public function remove( $name ) {
		delete_network_option( null, $this->get_option_name( $name ) );
	}

	/**
	 * Sets an option. Overwrites the existing option if the name is already in use.
	 *
	 * @param string $name The option name.
	 * @param mixed  $value The option value.
	 */
	public function set( $name, $value ) {
		update_network_option( null, $this->get_option_name( $name ), $value );
	}
}
