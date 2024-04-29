<?php
/**
 * Chargebee Init
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/Chargebee
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\Chargebee;

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
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Get User By Email
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->hooks();

		new ChargebeeApi();
		new CreditCardAlert();
		new Customer();
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
		add_filter( 'script_loader_tag', array( $this, 'script_attrs'), 10, 3 );
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
		$file        = 'src/js/emailLookup.js';
		$file_path   = KITCES_DIR_PATH . $file;
		$file_url    = KITCES_DIR_URL . $file;
		$file_exists = file_exists( $file_path );
		$file_time   = $file_exists ? filemtime( $file_path ) : '1.0.0';
		$handle      = 'kitces-email-lookup';

		if ( $file_exists && is_page( 'become-member-for-imca-ce-and-cfp-ce-credits' ) ) {
			wp_enqueue_script( 'chargebee-dropin', 'https://js.chargebee.com/v2/chargebee.js', array(), '1.0.0', true );
			wp_register_script( $handle, $file_url, array( 'kitces-core-scripts', 'chargebee-dropin' ), $file_time, true );
			wp_enqueue_script( $handle );
		}
	}

	/**
	 * Script Attributes
	 *
	 * Add the site data attribute to the
	 * Chargebee dropin script.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $tag    The script tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $source The script's source URL.
	 *
	 * @return string
	 */
	public function script_attrs( $tag, $handle, $source ) {
		if ( 'chargebee-dropin' === $handle ) {
			$tag = '<script type="text/javascript" src="' . $source . '" data-cb-site="kitces"></script>';
		}

		return $tag;
	}
}
