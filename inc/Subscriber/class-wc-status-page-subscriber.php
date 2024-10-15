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
use DKO\CON\EventManagement\Generator_Interface;

/**
 * WC status page subscriber.
 *
 * @author Walger Marketing
 * @since 1.0.0
 */
class WC_Status_Page_Subscriber implements Subscriber_Interface {

	const SLUG = 'wc-custom-order-num-status';

	/**
	 * The content generator.
	 *
	 * @var Generator_Interface
	 */
	protected $generator;


	/**
	 * Constructor.
	 *
	 * @param Generator_Interface $generator The content generator.
	 */
	public function __construct( Generator_Interface $generator ) {
		$this->generator = $generator;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function get_subscribed_events() {
		return array(
			'woocommerce_admin_status_tabs' => array( 'configure', 99, 1 ),
			'woocommerce_admin_status_content_' . self::SLUG => 'render',
		);
	}

	/**
	 * Configure Woocommerce admin status tabs
	 *
	 * @param  array $tabs Status tabs.
	 *
	 * @return void
	 */
	public function configure( array $tabs ) {
		$tabs[ self::SLUG ] = __( 'Custom Order Number', 'wc-custom-order-num' );
		return $tabs;
	}

	/**
	 * Render page
	 *
	 * @return void
	 */
	public function render() {
		echo $this->generator->generate( array() );  // phpcs:ignore WordPress.Security.EscapeOutput
	}
}
