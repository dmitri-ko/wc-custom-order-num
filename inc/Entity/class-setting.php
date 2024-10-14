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
 * @subpackage CON/entity
 */

namespace DKO\CON\Entity;

/**
 * Setting sedcriber entity
 *
 * @author Walger Marketing
 */
class Setting {
	/**
	 * Setting name.
	 *
	 * @var string
	 */
	protected $name;
	/**
	 * Setting description.
	 *
	 * @var  string
	 */
	protected $description;
	/**
	 * Setting default value.
	 *
	 * @var mixed
	 */
	protected $default;
	/**
	 * Setting display widget type.
	 *
	 * @var string
	 */
	protected $widget;

	/**
	 * Default constructor
	 *
	 * @param  string $name Setting name.
	 * @param  string $description Setting description.
	 * @param  mixed  $default Setting default value.
	 * @param  string $widget Setting display widget type.
	 */
	public function __construct( string $name, string $description, mixed $default = '', string $widget = 'text' ) {
		$this->name        = $name;
		$this->description = $description;
		$this->default     = $default;
		$this->widget      = $widget;
	}

	/**
	 * Set setting name
	 *
	 * @param  string $name New name.
	 *
	 * @return void
	 */
	public function set_name( string $name ) {
		$this->name = $name;
	}

	/**
	 * Get setting name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Set setting description
	 *
	 * @param  string $description New description.
	 *
	 * @return void
	 */
	public function set_description( string $description ) {
		$this->description = $description;
	}

	/**
	 * Get setting description
	 *
	 * @return string
	 */
	public function get_description(): string {
		return $this->description;
	}

	/**
	 * Set setting widget
	 *
	 * @param  string $widget New widget.
	 *
	 * @return void
	 */
	public function set_widget( string $widget ) {
		$this->widget = $widget;
	}

	/**
	 * Get setting widget
	 *
	 * @return string
	 */
	public function get_widget(): string {
		return $this->widget;
	}

	/**
	 * Set setting default
	 *
	 * @param  mixed $default New default.
	 *
	 * @return void
	 */
	public function set_default( mixed $default ) {
		$this->default = $default;
	}

	/**
	 * Get setting default
	 *
	 * @return mixed
	 */
	public function get_default(): mixed {
		return $this->default;
	}
}
