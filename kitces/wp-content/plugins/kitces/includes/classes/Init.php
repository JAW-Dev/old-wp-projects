<?php
/**
 * Init.
 *
 * @package    Kitces
 * @subpackage Kitces/Inlcudes/Classes
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Init.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Init {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init_classes();
		$this->hooks();
	}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'styles' ) );
	}

	/**
	 * Init Classes
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init_classes() {
		new Utils\Init();
		new SearchWP\Init();
		new WPRocket\Init();
		new Chargebee\Init();
		new GroupPriceQuote\Init();
		new ActiveCampaign\Init();
		new User\Init();
		new Exponea\Init();
		new FavoritePosts\Init();
		new Shortcodes\Init();
	}

	/**
	 * Scripts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function scripts() {
		$file        = 'src/js/index.js';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-core-scripts';

		$alert = new Chargebee\CreditCardAlert();
		$card  = new Chargebee\CreditCard();

		if ( $file_exists ) {
			wp_register_script( $handle, $file_url, array( 'jquery' ), $file_time, true );
			wp_enqueue_script( $handle );

			wp_localize_script(
				$handle,
				KITCES_PRFIX . 'Data',
				array(
					'adminAjax'  => admin_url( 'admin-ajax.php' ),
					'memberPage' => $alert->is_member_page(),
					'expireSoon' => $card->card_to_expire(),
				)
			);
		}
	}

	/**
	 * Styles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function styles() {
		$file        = 'src/css/index.css';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-core';

		if ( $file_exists ) {
			wp_register_style( $handle, $file_url, array(), $file_time, 'all' );
			wp_enqueue_style( $handle );
		}
	}
}
