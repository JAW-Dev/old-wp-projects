<?php

/*
Template Name: Account Page
*/

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
	global $CGD_CECredits;
	$can_update_numbers = kitces_is_valid_basic_member() || kitces_is_valid_premier_member();
	$can_update_billing = kitces_is_valid_basic_member() || kitces_is_valid_premier_member();
	?>
	<?php if ( $can_update_numbers ) : ?>
		<div class="account-block one-half first cfp-ce-number">
			<i class="fas fa-credit-card"></i>
			<h4>Update Your CFP / ACC / IWI / CPA / PTIN / CRD CE Numbers</h4>
			<?php
			echo do_shortcode( '[kitces_members_credits_form]' );
			echo do_shortcode( '[quiz-history-page-link classes="user-quiz-page-link-wrapper"]' );
			?>
		</div>
	<?php endif; ?>

	<?php if ( $can_update_billing ) : ?>
		<div class="account-block one-half billing-change">
			<i class="fas fa-credit-card"></i>
			<h4>Update Billing Information<br> or Cancel Membership</h4>
			<?php if ( kitces_is_valid_premier_member() ) : ?>
				<p style="text-align: left;">Membership Level: Premier</p>
			<?php elseif ( kitces_is_valid_basic_member() ) : ?>
				<p style="text-align: left;">Membership Level: Basic</p>
			<?php elseif ( kitces_is_valid_student_member() ) : ?>
				<p style="text-align: left;">Membership Level: Student</p>
			<?php endif; ?>
			<?php echo do_shortcode( '[chargebee_sso no_access="To update your billing information, please contact <a href=\'mailto:members@kitces.com\'>members@kitces.com</a>."]' ); ?>
		</div>
	<?php endif; ?>

	<div class="account-block one-half first password-change">
		<i class="fas fa-key"></i>
		<h4>Change Your <br> Password</h4>
		<?php echo do_shortcode( '[kitces_password_reset_form]' ); ?>
	</div>

	<div class="account-block one-half email-change">
		<i class="fas fa-envelope-square"></i>
		<h4>Change Your <br> Email Address</h4>
		<?php echo do_shortcode( '[gravityform id="99" title="false" ajax=true description="false"]' ); ?>
		<p class="disclaimer"><?php echo get_post_meta( get_the_ID(), 'kitces_email_disclaimer', true ); ?></p>
	</div>
	<?php
}

genesis();
