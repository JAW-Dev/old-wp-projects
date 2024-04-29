<?php
/**
 * WPRocket.
 *
 * @package    Kitces
 * @subpackage Kitces/Includes/Classes/WPRocket
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @since      1.0.0
 */

namespace Kitces\Includes\Classes\WPRocket;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * WPRocket.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Wprocket {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
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
		add_action( 'wp_rocket_loaded', array( $this, 'remove_actions' ) );
	}

	/**
	 * Remove Actions
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function remove_actions() {
		remove_action( 'user_register', 'rocket_clean_domain' );
		remove_action( 'profile_update', 'rocket_clean_domain' );
		remove_action( 'deleted_user', 'rocket_clean_domain' );
		remove_action( 'create_term', 'rocket_clean_domain' );
		remove_action( 'edited_terms', 'rocket_clean_domain' );
		remove_action( 'delete_term', 'rocket_clean_domain' );
		remove_action( 'add_link', 'rocket_clean_domain' );
		remove_action( 'edit_link', 'rocket_clean_domain' );
		remove_action( 'delete_link', 'rocket_clean_domain' );
	}
}
