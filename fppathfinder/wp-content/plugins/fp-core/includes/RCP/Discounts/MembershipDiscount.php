<?php
/**
 * Membership Discount
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\RCP\Discounts;

use FP_Core\Admin\RCP\Discounts\MemberDiscountCodes;
use FP_Core\Utilities\Membership\Memberships;
use FP_Core\Group_Settings\Database;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Membership Discount
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MembershipDiscount {

	/**
	 * User ID
	 *
	 * @var int
	 */
	protected $user_id = 0;

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The User ID.
	 *
	 * @return void
	 */
	public function __construct( int $user_id = null ) {
		$this->set_member_user_id( $user_id );

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
		add_action( 'wp_login', [ $this, 'set_group_discounts' ] );
		add_action( 'switch_to_user', [ $this, 'set_group_discounts' ] );
		add_action( 'admin_init', [ $this, 'remove_duplicate_discounts' ] );
		add_action( 'wp_login', [ $this, 'remove_duplicate_discounts' ] );
		add_action( 'switch_to_user', [ $this, 'remove_duplicate_discounts' ] );
	}

	/**
	 * Set Group Discounts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function set_group_discounts() {
		$user_id      = get_current_user_id();
		$group_member = rcpga_user_is_group_member( $user_id );

		if ( ! $group_member ) {
			return;
		}

		$group_id = fp_get_group_id( $user_id );

		$group_disount = ! empty( $group_id ) ? Database::get_group_setting( $group_id, 'Group_Members_Discount_Code' ) : '';

		if ( empty( $group_disount ) ) {
			return;
		}

		$memberships = ( new Memberships() )->get( $user_id );

		if ( empty( $memberships ) ) {
			return;
		}

		$membership_id = ! empty( $memberships[0] ) ? $memberships[0] : 0;

		if ( ! $membership_id ) {
			return;
		}

		( new MemberDiscountCodes() )->save( $group_disount, $membership_id );
	}

	/**
	 * Member Has Discount
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The user ID.
	 *
	 * @return mixed
	 */
	public function member_has_discount( int $user_id = null ) {
		$user_id   = ! is_null( $user_id ) ? $user_id : $this->get_member_user_id();
		$discounts = get_user_meta( $this->user_id, 'rcp_user_discounts', true );

		if ( empty( $discounts ) ) {
			return false;
		}

		return $discounts;
	}

	/**
	 * Remove Invalid Discounts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function remove_duplicate_discounts() {
		$discounts = $this->member_has_discount();

		if ( empty( $discounts ) || count( $discounts ) <= 1 ) {
			return;
		}

		$rcp_discounts = function_exists( 'rcp_get_discounts' ) ? wp_list_pluck( rcp_get_discounts( [ 'number' => 999 ] ), 'code' ) : [];
		$new_discounts = [];

		foreach ( $discounts as $discount ) {
			if ( in_array( $discount, $rcp_discounts, true ) ) {
				$new_discounts[] = $discount;
			}
		}

		return update_user_meta( $this->get_member_user_id(), 'rcp_user_discounts', array_unique( $new_discounts ) );
	}

	/**
	 * Set Member User ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param int $user_id The User ID.
	 *
	 * @return int
	 */
	public function set_member_user_id( int $user_id = null ) {
		$user_id       = ! is_null( $user_id ) ? $user_id : get_current_user_id();
		$membership_id = ! empty( $_GET['membership_id'] ) ? sanitize_text_field( wp_unslash( $_GET['membership_id'] ) ) : 0;

		if ( is_admin() && $membership_id ) {
			$membership = function_exists( 'rcp_get_membership' ) ? rcp_get_membership( $membership_id ) : false;

			if ( ! $membership ) {
				return 0;
			}

			$this->user_id = $membership->get_user_id();

			return $this->user_id;
		}

		$this->user_id = $user_id;

		return $this->user_id;
	}

	/**
	 * Get Member User ID
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_member_user_id() {
		return $this->user_id ? $this->user_id : $this->set_member_user_id();
	}
}
