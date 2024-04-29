<?php

namespace FP_Core\Group_Settings;

use FP_Core\Group_Settings\Settings\GroupMembersDiscountCode;
class Admin_Group_Settings extends Settings_Form {
	public function __construct() {}

	public function get_name() {
		return 'admin-group-settings';
	}

	public function set_fields() {
		$array = array(
			new GroupMembersDiscountCode(),
		);

		$group_id = absint( $_GET['rcpga-group'] ?? 0 );

		$membership_level = '';

		if ( $group_id ) {
			$group = rcpga_get_group( $group_id );

			if ( empty( $group ) ) {
				return;
			}

			$customer          = rcp_get_customer_by_user_id( $group->get_owner_id() );
			$owner_memberships = ! empty( $customer ) ? $customer->get_memberships() : false;
			$membership_level  = '';

			if ( empty( $owner_memberships ) ) {
				return;
			}

			foreach ( $owner_memberships as $owner_membership ) {
				if ( ! empty( $owner_membership->get_object_id() ) ) {
					$membership_level = $owner_membership->get_object_id();
				}
			}
		}

		if ( $membership_level === '8' || $membership_level === '7' ) {
			return;
		}

		$this->fields = array_filter(
			$array
		);
	}

	public function add_hooks() {
		add_action( 'restrict_page_rcp-groups', array( $this, 'output_settings_form' ), 1000 );
	}

	public function get_actions(): array {
		return array(
			'form_submit' => array( $this, 'handle_form_submit' ),
		);
	}

	public function handle_form_submit() {
		$group_id = absint( $_GET['rcpga-group'] );

		if ( ! $group_id ) {
			return;
		}

		foreach ( $this->fields as $field ) {
			$field->handle_submit( $group_id );
		}
	}

	public function output_settings_form() {
		$group_id = sanitize_text_field( wp_unslash( absint( $_GET['rcpga-group'] ?? '' ) ) );

		if ( ! $group_id ) {
			return;
		}

		if ( empty( $this->fields ) ) {
			return;
		}

		?>
		<div style="padding: 1rem;">
			<h2>fpPathfinder Group Discount Code:</h2>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<?php $this->get_nonce_action_field( 'form_submit' ); ?>

				<?php
				foreach ( $this->fields as $field ) {
					$field->output_field( $group_id );
				}
				?>

				<input style="display: block; margin-top: 2rem;" class="button button-primary" type="submit" value="Update Group" />
			</form>
			<br/>
			<h2>fpPathfinder Group ActiveCampaign Re-sync:</h2>
			<?php
			$nonce = wp_create_nonce( 'sync-group-members' );
			$group = sanitize_text_field( wp_unslash( $_GET['rcpga-group'] ) );
			?>
			<a href="" id="member-group-sync-button" class="button button-primary" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-groupid="<?php echo esc_attr( $group ); ?>"><?php _e( 'Sync Group Members', 'fp-core' ); ?></a>
			<span id="progress-indicator" style="position: relative; display: none">
				<progress id="progress-bar" value="0" max="100" style="width: 300px; height: 32px; margin-left: 0.5rem;"></progress>
				<span id="progress-total" style="top: -8px; position: relative; margin-left: 0.5rem;">0%</span>
			</span>

			<?php do_action( 'after_custom_group_settings_fields' ); ?>

		</div>
		<?php
	}
}
