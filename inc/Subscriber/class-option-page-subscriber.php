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
use DKO\CON\Generator\Template_Generator;

/**
 * Option page subscriber that registers plugin settings page in Admin UI with WordPress.
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class Option_Page_Subscriber implements Subscriber_Interface {
	/**
	 * Option page generator.
	 *
	 * @var Template_Generator
	 */
	private $generator;


	/**
	 * Constructor.
	 *
	 * @param Template_Generator $generator Option page generator.
	 */
	public function __construct( $generator ) {
		$this->generator = $generator;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'admin_menu' => 'create_menu',
			'admin_init' => 'register_settings',
		);
	}

	/**
	 * Add settings page to Admin menu
	 *
	 * @return void
	 */
	public function create_menu() {
		$svg = '<svg  height="200px" width="200px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" >
		<path fill="#000000" d="M479.319,114.383c0,72.081-58.643,130.723-130.723,130.724l-81.702-0.001v-57.234l24.554,24.553l46.218,0.001 l-69.213-69.211c1.939-12.455,5.648-24.329,10.852-35.367l99.733,99.733c11.011-3.606,21.175-9.107,30.091-16.126L297.328,79.654 c7.014-8.384,15.055-15.878,23.932-22.288l109.875,109.876c6.339-9.865,10.958-20.935,13.433-32.784l-93.286-93.286 c14.409-5.48,30.026-8.491,46.335-8.491V0c-90.101,0-163.404,73.303-163.404,163.404v76.255h-74.077 C71.837,239.66,0,311.496,0,399.796h32.681c0-70.279,57.176-127.455,127.455-127.455h79.523v57.763l-25.083-25.083H168.36 l69.316,69.315c-2.187,12.244-6.135,23.884-11.56,34.658l-98.295-98.296c-10.766,3.917-20.625,9.735-29.175,17.042L207.41,436.504 c-7.277,8.175-15.584,15.409-24.721,21.497L77.725,353.037c-5.962,10.468-9.991,22.173-11.589,34.629l85.426,85.426 c-12.402,4.037-25.627,6.228-39.358,6.228V512c88.299,0,160.136-71.837,160.136-160.136v-74.077l76.255,0.001 C438.697,277.787,512,204.484,512,114.383H479.319z"></path>
		 </svg>';

		add_menu_page(
			__( 'DNA Test', 'dog-dna-tests' ),
			__( 'DNA Test', 'dog-dna-tests' ),
			'administrator',
			'CON-settins-page',
			array( $this->generator->generate() ),
			'data:image/svg+xml;base64,' . base64_encode( $svg )
		);
	}


	public function register_settings() {
		return '';
	}
}
