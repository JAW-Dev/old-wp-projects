<?php
/**
 * Partnership Registration
 *
 * @package    FP_Core/
 * @subpackage FP_Core/Includes/RCP
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core\RCP;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Partnership Registration
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class PartnershipRegistration {

	/**
	 * Initialize the class
	 *
	 * @author Jason Witt
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
		add_action( 'rcp_registration_init', [ $this, 'maybe_apply_partnership_discount' ] );
	}

	/**
	 * Maybe apply partnership Discount
	 *
	 * Apply partnership discount if upgrading.
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function maybe_apply_partnership_discount( $registration ) {
		if ( ! in_array( $registration->get_registration_type(), array( 'upgrade', 'downgrade' ), true ) ) {
			return;
		}

		$level_id = ! empty( $_REQUEST['rcp_level'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['rcp_level'] ) ) : '';

		if ( empty( $level_id ) ) {
			return;
		}

		$user_id          = get_current_user_id();
		// $set_discounts    = function_exists( 'get_field' ) ? get_field( 'partnership_discount_codes', 'option' ) : [];
		$user_discounts   = get_user_meta( $user_id, 'rcp_user_discounts', true );
		$has_discount     = false;
		$applied_discount = '';

		$set_discounts = [
			[ 'discount_codes' => 'napfamember10' ],
			[ 'discount_codes' => 'napfa10' ],
		];

		if ( empty( $user_discounts ) || empty( $set_discounts ) ) {
			return;
		}

		foreach ( $user_discounts as $user_discount ) {
			foreach ( $set_discounts as $set_discount ) {
				if ( $set_discount['discount_codes'] === $user_discount ) {
					$has_discount     = true;
					$applied_discount = $user_discount;
					break;
				}
			}
		}

		if ( empty( $applied_discount ) || ! $has_discount ) {
			return;
		}

		$discount       = rcp_get_discount_by( 'code', $applied_discount );
		$old_membership = $registration->get_membership();
		$old_discount   = $this->get_discount_amount( $discount, $old_membership->get_object_id() );
		$new_discount   = $this->get_discount_amount( $discount, $level_id );
		$total_discount = $new_discount - $old_discount;

		if ( $new_discount > 0 ) {
			$registration->add_fee( - $new_discount, __( 'Partnership Discount', 'rcp' ), false, false );
		}
	}

	/**
	 * Get Discount
	 *
	 * @author Jason Witt
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function get_discount_amount( \RCP_Discount $discount, int $level_id ) {
		if ( empty( $discount ) || empty( $level_id ) ) {
			return 0;
		}

		$price  = rcp_get_membership_level( $level_id )->get_price();
		$amount = rcp_get_discounted_price( $price, $discount->get_amount(), $discount->get_unit() );

		return $price - $amount;
	}
}
