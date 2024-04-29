<?php
/**
 * Base
 *
 * @package    Kitces_Members
 * @subpackage Kitces_Members/Inlcudes/Classes/MemberActions/Merge
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace Kitces_Members\Includes\Classes\MemberActions\Merge;

/**
 * Base
 *
 * @author Jason Witt
 * @since  1.0.0
 */
abstract class Base {
	/**
	 * Source User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var \WP_User
	 */
	protected $source_wp_user = false;

	/**
	 * Destination User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var \WP_User
	 */
	protected $destination_wp_user = false;

	/**
	 * Base constructor.
	 */
	public function __construct() {}

	/**
	 * Merge
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function merge() {
		if ( $this->has_valid_users() && $this->is_available() ) {
			return $this->do_merge();
		}

		return false;
	}

	/**
	 * Is Available
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function is_available() {
		return true;
	}

	/**
	 * Do Merge
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function do_merge() {
		return true;
	}

	/**
	 * Has Valid Users
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return boolean
	 */
	public function has_valid_users() {
		/*
		 * Sanity checks
		 */
		if ( ! is_a( $this->get_source_wp_user(), 'WP_User' ) || $this->get_source_wp_user()->ID === 0 ) {
			return false;
		}

		if ( ! is_a( $this->get_destination_wp_user(), 'WP_User' ) || $this->get_destination_wp_user()->ID === 0 ) {
			return false;
		}

		return true;
	}

	/**
	 * Get Source WP User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return \WP_User
	 */
	public function get_source_wp_user() {
		return $this->source_wp_user;
	}

	/**
	 * Set Source WP User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param \WP_User $source_wp_user The source WP User.
	 *
	 * @return \WP_User
	 */
	public function set_source_wp_user( $source_wp_user ) {
		$this->source_wp_user = $source_wp_user;

		return $this;
	}

	/**
	 * Get Destination WP User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return \WP_User
	 */
	public function get_destination_wp_user() {
		return $this->destination_wp_user;
	}

	/**
	 * Set Destination WP User
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param \WP_User $destination_wp_user The destination WP user.
	 *
	 * @return \WP_User
	 */
	public function set_destination_wp_user( $destination_wp_user ) {
		$this->destination_wp_user = $destination_wp_user;

		return $this;
	}
}
