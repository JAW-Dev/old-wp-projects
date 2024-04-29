<?php
/**
 * Member Discount Codes
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/Admin/RCP/Discounts
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\Admin\RCP\Discounts;

use FP_Core\Existing_Membership_Discounter;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Member Discount Codes
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class MemberDiscountCodes {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Hooks
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param string $value The discount code value to save.
	 *
	 * @return void
	 */
	public function save( $value, $membership_id = ''  ) {
		if ( empty( $value ) ) {
			return;
		}

		if ( ! isset( $_GET['membership_id'] ) ) {
			return;
		}

		$applied_codes    = ! empty( $this->get_applied_codes() ) ? $this->get_applied_codes() : [];
		$membership_param = ! empty( $_GET['membership_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['membership_id'] ) ) ) : 0;
		$membership_id    = ! empty( $membershiop_id ) ? $membershiop_id : $membership_param;

		$new_codes = [];

		if ( ! in_array( $value, $applied_codes, true ) ) {
			$new_codes[] = $value;
		}

		foreach ( $applied_codes as $applied_code ) {
			$new_codes[] = $applied_code;
		}

		Existing_Membership_Discounter::apply_discount( $membership_id, $value );

		return update_user_meta( $this->get_user_id(), 'rcp_user_discounts', $new_codes );
	}

	/**
	 * Remove Discounts
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @param array $discounts The applied member discounts.
	 *
	 * @return void
	 */
	public function remove() {
		$discounts   = $this->get_applied_codes();
		$remove_code = isset( $_GET['rcp_remove_code'] ) ? sanitize_text_field( wp_unslash( $_GET['rcp_remove_code'] ) ) : '';

		if ( empty( $remove_code ) ) {
			return;
		}

		$new_codes = [];

		foreach ( $discounts as $discount ) {
			if ( $remove_code !== $discount ) {
				$new_codes[] = $discount;
			}
		}

		$membership_id = isset( $_GET['membership_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['membership_id'] ) ) ) : '';

		Existing_Membership_Discounter::apply_discount( $membership_id, '' );

		return update_user_meta( $this->get_user_id(), 'rcp_user_discounts', $new_codes );
	}

	/**
	 * Get Applied Codes
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return array
	 */
	public function get_applied_codes() {
		$membership_id = isset( $_GET['membership_id'] ) ? sanitize_text_field( wp_unslash( $_GET['membership_id'] ) ) : 0;

		if ( ! $membership_id ) {
			return [];
		}

		$applied_codes = get_user_meta( $this->get_user_id(), 'rcp_user_discounts', true );

		return $applied_codes;
	}

	/**
	 * Get Membership
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return RCP_Membership
	 */
	public function get_membership() {
		$membership_id = isset( $_GET['membership_id'] ) ? absint( sanitize_text_field( wp_unslash( $_GET['membership_id'] ) ) ) : '';

		if ( empty( $membership_id ) ) {
			return '';
		}

		$membership = function_exists( 'rcp_get_membership' ) ? rcp_get_membership( $membership_id ) : '';

		return $membership;
	}

	/**
	 * Get User Id
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function get_user_id( $membership = '' ) {
		$membership = empty( $membership ) ? $this->get_membership() : $membership;
		$user_id    = $membership->get_user_id();

		return $user_id;
	}
}
