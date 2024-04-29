<?php

/*
Template Name: Password Reset
*/

use Kitces_Members\Includes\Classes\Login\PasswordLink;
use Kitces_Members\Includes\Classes\Utilities\SurveyUrl;

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'account-page';
	return $classes;

}
// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cgd_password_account_reset' );
function cgd_password_account_reset() {
	$password_link           = PasswordLink::instance();
	$key_exists_and_is_valid = $password_link->key_exists_and_is_valid();
	$expired_message         = function_exists( 'get_field' ) ? get_field( 'kitces_new_member_expired_link_message', 'option' ) : '';

	if ( ! $key_exists_and_is_valid ) {
		echo wp_kses_post( $expired_message );
		return;
	}

	form_markup();
}

function form_markup() {
	$member_url     = home_url( 'member' );
	$is_member_link = function_exists( 'kitces_is_new_member_link' ) ? kitces_is_new_member_link() : false;
	$redirect_url   = function_exists( 'kitces_get_survey_url' ) ? kitces_get_survey_url() : '';

	if ( $is_member_link ) {
		$member_url = add_query_arg( array( 'password-reset' => true ), home_url( 'member' ) );
	}

	if ( ! empty( $redirect_url ) ) {
		$is_survey_url_link = function_exists( 'kitces_is_survey_url_link' ) ? kitces_is_survey_url_link() : false;

		if ( $is_survey_url_link ) {
			$member_url = add_query_arg(
				array(
					'redirect_url' => $redirect_url,
				),
				$redirect_url
			);
		}
	}
	?>
	<div class="account-block one-half password-change centered">
		<i class="fas fa-key"></i>
		<h4>Change Your <br> Password</h4>
		<?php echo do_shortcode( '[kitces_password_reset_form redirect_url="' . $member_url . '"]' ); ?>
	</div>
	<?php
}

genesis();
