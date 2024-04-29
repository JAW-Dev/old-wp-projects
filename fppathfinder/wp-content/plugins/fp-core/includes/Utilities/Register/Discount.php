<?php
/**
 * Discount.
 *
 * @package    FP_Core
 * @subpackage FP_Core/Inlcudes/Utilities/Register
 * @author     Objectiv
 * @copyright  Copyright (c) 2021, Objectiv
 * @license    GNU General Public License v2 or later
 * @since      1.0.0
 */

namespace FP_Core\Utilities\Register;

use FP_Core\Member;
use FP_Core\Group_Settings\Database;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Discount.
 *
 * @author Objectiv
 * @since  1.0.0
 */
class Discount {

	/**
	 * Initialize the class
	 *
	 * @author Objectiv
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function __construct() {}

	/**
	 * Get
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function get() {
		$discount = '';

		if ( ! empty( $_REQUEST['discount'] ) ) {
			$discount = sanitize_text_field( wp_unslash( $_REQUEST['discount'] ) );
		}

		if ( ! empty( $_REQUEST['partnership'] ) ) {
			$partnership_type = sanitize_text_field( wp_unslash( $_REQUEST['partnership'] ) );
			// $set_discounts    = function_exists( 'get_field' ) ? get_field( 'partnership_discount_codes', 'option' ) : [];
			$set_discounts = [
				[ 'discount_codes' => 'napfamember10' ],
				[ 'discount_codes' => 'napfa10' ],
			];

			if ( empty( $discounts ) ) {
				$discount = '';
			}

			if ( ! empty( $set_discounts ) ) {
				foreach ( $set_discounts as $set_discount ) {
					foreach ( $set_discount as $key => $value ) {
						if ( $value === $partnership_type ) {
							$discount = $value;
							break;
						}
					}
				}
			}
		}

		$user_id        = get_current_user_id();
		$user_discounts = get_user_meta( $user_id, 'rcp_user_discounts', true );

		if ( ! empty( $user_discounts ) ) {
			$discount = ! empty( $user_discounts[0] ) ? $user_discounts[0] : '';
		}

		if ( empty( $discount ) ) {
			$discount = 'none';
		}

		$group_member = function_exists( 'rcpga_user_is_group_member' ) ? rcpga_user_is_group_member( $user_id ) : false;

		if ( $group_member ) {
			$member   = new Member( $user_id );
			$group    = ! empty( $member ) && method_exists( $member, 'get_group' ) ? $member->get_group() : '';
			$group_id = ! empty( $group ) && method_exists( $group, 'get_group_id' ) ? $group->get_group_id() : '';
			$discount = ! empty( $group_id ) ? Database::get_group_setting( $group_id, 'Group_Members_Discount_Code' ) : 'none';
		}

		return apply_filters( FP_CORE_PREFIX . '_get_register_discount', $discount );
	}
}
