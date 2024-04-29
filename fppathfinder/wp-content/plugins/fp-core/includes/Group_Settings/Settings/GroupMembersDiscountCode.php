<?php

namespace FP_Core\Group_Settings\Settings;
use \FP_Core\Member;
use \FP_Core\Group_Settings\Database;

class GroupMembersDiscountCode extends Text {

	public function __construct() {}

	public function get_label(): string {
		return 'Group Member Discount Code';
	}

	public function add_hooks() {
		add_filter( 'registration_discount', array( $this, 'maybe_add_discount' ), 10, 3 );
		add_filter( 'rcp_is_discount_valid', array( $this, 'maybe_invalidate_discount' ), 10, 3 );
	}

	public function discount_existing_membership() {
		$membership_id = 1727;
		$discount_code = 'one';

		\FP_Core\Existing_Membership_Discounter::apply_discount( $membership_id, $discount_code );
	}

	public function maybe_add_discount( $discount_code ) {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return $discount_code;
		}

		$member = new Member( $user_id );

		if ( ! $member->get_group() ) {
			return $discount_code;
		}

		$group_code = $this->get( $member->get_group()->get_group_id() );

		if ( ! $group_code || ! rcp_validate_discount( $group_code ) ) {
			return $discount_code;
		}

		return $group_code;
	}

	public function maybe_notify_of_discount_code() {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return;
		}

		$member = new Member( $user_id );

		if ( ! $member->get_group() || ! $member->get_group()->is_active() ) {
			return;
		}

		$group_code = $this->get( $member->get_group()->get_group_id() );

		if ( ! $group_code || ! rcp_validate_discount( $group_code ) ) {
			return;
		}

		?>
		<div class="group_discount_notification">
			<h5>You have a group discount available! Use the code: <pre><?php echo $group_code; ?></pre></h5>
		</div>
		<?php
	}

	public function maybe_invalidate_discount( $is_valid, $discount_object, $membership_level_id ) {
		if ( ! $discount_object ) {
			return $is_valid;
		}

		if ( ! $is_valid ) {
			return $is_valid; // we're only possibly invalidating, no need to run the logic if it's already invalid.
		}

		$code            = $discount_object->get_code();
		$all_group_codes = $this->get_all_group_discount_codes();

		if ( ! in_array( $code, $all_group_codes ) ) {
			return $is_valid;
		}

		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return false; // this is a group code and the user isn't logged in.
		}

		$member = new Member( $user_id );

		if ( ! $member->get_group() ) {
			return false; // member isn't in a group or group is inactive
		}

		$group_code = $this->get( $member->get_group()->get_group_id() );

		if ( $code !== $group_code ) {
			return false;
		}

		return true;
	}

	private function get_all_group_discount_codes(): array {
		$rows  = Database::get_group_settings_rows( 0, $this->get_name() );
		$codes = array();

		foreach ( $rows as $row ) {
			if ( ! $row->value ) {
				continue;
			}

			$codes[] = $row->value;
		}

		return $codes;
	}
}
