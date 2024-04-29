<?php

namespace FP_Core;

class Existing_Membership_Discounter {
	public function __construct() {}

	static public function apply_discount( int $membership_id, string $discount_code ) {
		try {
			$membership = rcp_get_membership( $membership_id );

			if ( ! $membership->is_active() ) {
				return;
			}

			$subscription_id = $membership->get_gateway_subscription_id();

			if ( ! $subscription_id ) {
				return;
			}

			$membership_level = rcp_get_membership_level( $membership->get_object_id() );
			$base_price       = floatval( $membership_level->price );
			$discounted_price = $base_price;

			if ( ! empty( $discount_code ) ) {
				$discount_object  = rcp_get_discount_by( 'code', $discount_code );
				$discounted_price = rcp_get_discounted_price( $base_price, $discount_object->get_amount(), $discount_object->get_unit(), false );
			}

			$stripe_gateway = new \RCP_Payment_Gateway_Stripe();

			$stripe_gateway->init();

			if ( empty( $discount_code ) ) {
				$discounted_price = $base_price;
			}

			$stripe_plan_args = array(
				'name'           => $membership_level->name,
				'interval'       => $membership_level->duration_unit,
				'interval_count' => intval( $membership_level->duration ),
				'price'          => $discounted_price,
			);

			$new_plan_id = $stripe_gateway->maybe_create_plan( $stripe_plan_args );

			$membership->update( [ 'recurring_amount' => $discounted_price ] );

			self::change_stripe_subscription_plan( $membership->get_gateway_subscription_id(), $new_plan_id );

			return true;
		} catch ( \Exception $e ) {
			wp_mail(
				array( 'john@objectiv.co', 'mike@fppathfinder.com' ),
				'Error Discounting Deluxe Membership',
				$e->getMessage()
			);
			return false;
		}
	}

	/**
	 * Change Stripe Subscription Plan
	 *
	 * Takes a Stripe subscription ID and changes its plan in Stripe to the new plan ID.
	 *
	 * @param string $subscription_id The ID of the Stripe subscription.
	 * @param string $new_plan_id The ID of the Stripe Plan.
	 *
	 * @return
	 */
	static private function change_stripe_subscription_plan( $subscription_id, $new_plan_id ) {
		$subscription     = \Stripe\Subscription::retrieve( $subscription_id );
		$existing_item_id = $subscription->items->data[0]->id;
		$new_item         = array(
			'id'   => $existing_item_id,
			'plan' => $new_plan_id,
		);
		$items            = array( $new_item );
		$arguments        = array(
			'items' => $items,
		);

		$update = \Stripe\Subscription::update( $subscription_id, $arguments );

		return $update;
	}
}
