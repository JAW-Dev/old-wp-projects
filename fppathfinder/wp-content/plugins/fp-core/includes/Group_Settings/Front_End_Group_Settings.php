<?php

namespace FP_Core\Group_Settings;
use \FP_Core\Member;

class Front_End_Group_Settings extends Settings_Form {
	public function __construct() {}

	public function get_name() {
		return 'front-end-group-settings';
	}

	public function set_fields() {
		$this->fields = array_filter( array(
			Settings\No_Advisor_Names_On_PDFs::maybe_init(),
		) );
	}

	public function add_hooks() {
		add_action( 'fppathfinder_custom_group_settings', array( $this, 'maybe_output_settings_form' ), 1000 );
	}

	public function get_actions(): array {
		return array(
			'form_submit' => array( $this, 'handle_form_submit' ),
		);
	}

	public function handle_form_submit() {
		$user_id = get_current_user_id();
		$member  = new Member( $user_id );

		if ( ! $member->get_group() || ! $member->get_group()->get_group_id() ) {
			return;
		}

		$group_id = $member->get_group()->get_group_id();

		foreach ( $this->fields as $field ) {
			$field->handle_submit( $group_id );
		}
	}

	public function maybe_output_settings_form() {
		$user_id = get_current_user_id();
		$member  = new Member( $user_id );

		if ( ! $member->get_group() ) {
			return;
		}

		if ( $member->get_group()->get_owner_id() !== $user_id ) {
			return;
		}

		$group_id = $member->get_group()->get_group_id();

		$this->output_settings_form( $group_id );
	}

	public function output_settings_form( int $group_id ) {

		if ( empty( $this->fields ) ) {
			return;
		}

		?>
		<div style="padding: 1rem 0;">
			<h3 style="margin-bottom: 2rem;">General Group Settings</h3>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" style="margin-bottom: 2rem;">
				<?php $this->get_nonce_action_field( 'form_submit' ); ?>

				<?php
				foreach ( $this->fields as $field ) {
					$field->output_field( $group_id );
				}
				?>

				<input style="display: block; margin-top: 2rem;" class="button button-primary" type="submit" value="Update Group" />
			</form>
		</div>
		<?php
	}
}
