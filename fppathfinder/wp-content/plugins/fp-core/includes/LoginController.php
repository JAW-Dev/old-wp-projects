<?php

namespace FP_Core;

/**
 * Login Controller
 *
 * Login functionality customizations
 */
class LoginController {

	public function __construct() {
		$this->setup_rcp_login_redirects();
	}

	public function setup_rcp_login_redirects() {
		add_action( 'rcp_after_login_form_fields', array( $this, 'setup_redirect_form_field' ) );
		add_filter( 'rcp_login_redirect_url', array( $this, 'redirect_rcp_login' ), 10, 2 );
	}

	public function setup_redirect_form_field() {
		if ( $_REQUEST['redirect_to'] ?? false ) {
			?>
			<input type="hidden" name="fppathfinder_redirect_to" value="<?php echo $_REQUEST['redirect_to']; ?>">
			<?php
		}
	}

	public function redirect_rcp_login( $redirect, $user ) {
		if ( strpos( $redirect, 'checklist' ) !== false ) {
			return $redirect;
		}

		$member = new Member( $user->ID );
		$active = $member->is_active();

		if ( $active ) {
			$redirect = home_url( 'resources' );
		}

		return ( $_POST['fppathfinder_redirect_to'] ?? false ) ? $_POST['fppathfinder_redirect_to'] : $redirect;
	}

}
