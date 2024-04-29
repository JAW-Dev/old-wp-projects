<?php
/**
 * Proration Controller
 *
 * @package    FP_Core
 * @author     Objectiv
 * @copyright  Copyright (c) 2020, Objectiv
 * @license    GPL-2.0
 * @version    1.0.0
 */

namespace FP_Core;

if ( ! defined( 'WPINC' ) ) {
	wp_die( 'No Access Allowed!', 'Error!', array( 'back_link' => true ) );
}

/**
 * Proration Controller
 *
 * @author Jason Witt
 * @since  1.0.0
 */
class ProrationController {
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
	 * Initialize the class
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'rcp_membership_get_prorate_credit', [ $this, 'maybe_correct_proration_credit' ], 10, 3 );
		add_filter( 'rcp_membership_get_prorate_credit', [ $this, 'maybe_disable_proration_credit' ], 10, 2 );
	}

	/**
	 * Maybe Correct Proation Credit
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function maybe_correct_proration_credit( $credit, $membership_id, $membership ) {
		try {
			if ( 0 === $credit ) {
				return $credit;
			}

			// Make sure this is an active, paid membership.
			if ( ! $membership->is_active() || ! $membership->is_paid() || $membership->is_trialing() ) {
				return $credit;
			}

			// Get the most recent payment.
			foreach ( $membership->get_payments() as $pmt ) {
				if ( 'complete' != $pmt->status ) {
					continue;
				}

				$payment = $pmt;
				break;
			}

			if ( empty( $payment ) ) {
				return $credit;
			}

			if ( ! empty( $payment->object_id ) ) {
				$membership_level_id = absint( $payment->object_id );
				$membership_level    = rcp_get_membership_level( $membership_level_id );
			} else {
				$membership_level_id = rcp_get_membership_level_by( 'name', $payment->subscription );
				$membership_level    = $membership->get_object_id();
			}

			// Make sure the membership payment matches the existing membership.
			if ( empty( $membership_level->id ) || empty( $membership_level->duration ) || $membership_level->id !== $membership_level_id ) {
				return $credit;
			}

			$exp_date = $membership->get_expiration_date( false );

			// If this is member does not have an expiration date, they don't get any credits.
			if ( 'none' == $exp_date ) {
				return $credit;
			}

			// Make sure we have a valid date.
			if ( ! $exp_date = strtotime( $exp_date ) ) {
				return $credit;
			}

			$exp_date_dt = date( 'Y-m-d', $exp_date ) . ' 23:59:59';
			$exp_date    = strtotime( $exp_date_dt, current_time( 'timestamp' ) );

			$time_remaining = $exp_date - current_time( 'timestamp' );

			// Calculate the start date based on the expiration date.
			if ( ! $start_date = strtotime( $exp_date_dt . ' -' . $membership_level->duration . $membership_level->duration_unit, current_time( 'timestamp' ) ) ) {
				return $credit;
			}

			$total_time = $exp_date - $start_date;

			if ( $time_remaining <= 0 ) {
				return $credit;
			}

			/*
			* Calculate discount as percentage of membership remaining.
			* Use the subtotal from their last payment as the base price. This is the amount without discounts/credits/fees applied.
			* This was only added in version 2.9, so we use the full amount as a fallback in case the subtotal doesn't exist for the last payment.
			*/
			$payment_amount       = abs( $payment->amount -= $membership_level->fee );
			$percentage_remaining = $time_remaining / $total_time;

			// make sure we don't credit more than 100%
			if ( $percentage_remaining > 1 ) {
				$percentage_remaining = 1;
			}

			$discount = round( $payment_amount * $percentage_remaining, 2 );

			// Make sure they get a discount. This shouldn't ever run.
			if ( ! $discount > 0 ) {
				$discount = $payment_amount;
			}

			$discount = floatval( $discount );

			return $discount < $credit ? $discount : $credit;
		} catch ( \Exception $exception ) {
			return $credit;
		}
	}

	/**
	 * Maybe Disable Proration Credit
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return int
	 */
	public function maybe_disable_proration_credit( $credit, $membership_id ) {
		if ( self::is_disabled( $membership_id ) ) {
			return 0;
		}

		// Legacy functionality to remove credit from previously cancelled memberships
		if ( rcp_get_membership_meta( $membership_id, 'cancelled_by_enterprise_essentials_group_membership', true ) ) {
			return 0;
		}

		return $credit;
	}

	/**
	 * Get Meta Key
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	private function get_meta_key(): string {
		return 'fp_disable_proration_credit';
	}

	/**
	 * Disable
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function disable( int $membership_id ) {
		rcp_update_membership_meta( $membership_id, self::get_meta_key(), 'true' );
	}

	/**
	 * Is Disabled
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return string
	 */
	public function is_disabled( int $membership_id ) {
		return 'true' === rcp_get_membership_meta( $membership_id, self::get_meta_key(), true );
	}

	/**
	 * Undo Disable
	 *
	 * @author John Geesey|Jason Witt
	 * @since  1.0.0
	 *
	 * @return void
	 */
	public function undo_disable( int $membership_id ) {
		rcp_update_membership_meta( $membership_id, self::get_meta_key(), 'false' );
	}
}
