<?php

namespace FP_Core\Essentials_Trial_Membership;

class Registration_Field {
	static public function init() {
		add_action( 'rcp_after_register_form_fields', __CLASS__ . '::maybe_add_hidden_field' );
	}

	static public function maybe_add_hidden_field() {
		$essentials_trial = $_REQUEST['essentials_trial'] ?? null;

		if ( ! $essentials_trial ) {
			return;
		}

		$code = Settings::get_request_code();

		if ( ! $code ) {
			return;
		}

		?>
		<input type="hidden" name="essentials_trial" value="<?php echo $code['code']; ?>">
		<?php
	}
}
