<?php

set_time_limit( 0 );
require '../../wp/wp-load.php';
require '../vendor/autoload.php';

/**
 * Init
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class Init {

	/**
	 * AC API
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @var Type
	 */
	protected $ac;

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
	 * Init
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function init() {
		$this->add_new_roles();
	}

	/**
	 * Add New Roles
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function add_new_roles() {
		$users_args = array(
			'number' => -1,
		);

		$users = get_users( $users_args );

		foreach ( $users as $user ) {
			$memb_tags = get_user_meta( $user->ID, 'memb_tags', true );

			if ( empty( $memb_tags ) ) {
				continue;
			}

			$tags = explode( ',', $memb_tags );

			if ( $this->is_valid_basic_memberium_member( $tags ) ) {
				$user->add_role( 'basic' );
			}

			if ( $this->is_valid_student_memberium_member( $tags ) ) {
				$user->add_role( 'student' );
			}

			if ( $this->is_valid_premier_memberium_member( $tags ) ) {
				$user->add_role( 'premier' );
			}
		}
	}

	/**
	 * Is Valid Premier Memberium Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function is_valid_premier_memberium_member( $tags ) {
		if ( in_array( 'MemberAdmin-NewsletterMember', $tags, true ) && ! in_array( 'MemberAdmin-NewsletterMemberPAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Valid Basic Memberium Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function is_valid_basic_memberium_member( $tags ) {
		if ( in_array( 'MemberAdmin-Basic', $tags, true ) && ! in_array( 'MemberAdmin-Basic-PAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Is Valid Student Memberium Member
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function is_valid_student_memberium_member( $tags ) {
		if ( in_array( 'MemberAdmin-Student', $tags, true ) && ! in_array( 'MemberAdmin-StudentPAYF', $tags, true ) ) {
			return true;
		}

		return false;
	}
}

new init();

