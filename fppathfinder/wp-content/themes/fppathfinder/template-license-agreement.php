<?php

/*
Template Name: License Agreement
*/

use FpAccountSettings\Includes\Classes\Conditionals;

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'template-become-member template-license-agreement';
	return $classes;
}

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_license_agreement_page' );
function objectiv_license_agreement_page() {

	if ( is_user_logged_in() ) {
		$user            = wp_get_current_user();
		$user_id         = $user->ID;
		$email           = $user->user_email;
		$subscription    = rcp_get_subscription( $user_id );
		$member          = new \FP_Core\Member( $user_id );
		$subscription_id = '';

		if ( $subscription ) {
			$subscription_id = rcp_get_subscription_id( $user_id );
		} elseif ( $member->is_active_at_level( FP_ENTERPRISE_DELUXE_ID ) || Conditionals::is_deluxe_group_member() ) {
			$level           = rcp_get_membership_level( 4 );
			$subscription    = $level->name;
			$subscription_id = '4';
		} elseif ( $member->is_active_at_level( FP_ENTERPRISE_ESSENTIALS_ID ) || Conditionals::is_essentials_group_member() ) {
			$level           = rcp_get_membership_level( 5 );
			$subscription    = $level->name;
			$subscription_id = '5';
		} elseif ( $member->is_active_at_level( FP_ENTERPRISE_PREMIER_ID ) || Conditionals::is_premier_group_member() ) {
			$level           = rcp_get_membership_level( 8 );
			$subscription    = $level->name;
			$subscription_id = '8';
		}

		$user_already_agreed = get_user_meta( $user_id, 'rcp_privacy_policy_agreed', true );
	} else {
		$subscription_id = isset( $_GET['package'] ) && $_GET['package'] ? sanitize_text_field( $_GET['package'] ) : '1';
	}

	$subscription_id_to_license_agreement_field_map = array(
		'1' => 'fp_essentials_package',
		'2' => 'fp_deluxe_package',
		'3' => 'fp_firm_wide_enterprise_deluxe_package',
		'4' => 'fp_deluxe_package',
		'5' => 'fp_essentials_package',
		'6' => 'fp_premier_package',
		'7' => 'fp_premier_package',
		'8' => 'fp_premier_package',
	);

	$license_agreement = get_field( $subscription_id_to_license_agreement_field_map[ $subscription_id ] );
	$form_id           = get_field( 'fp_license_agreement_form_id', 'option' );
	$show_form         = is_user_logged_in() && ! $user_already_agreed && rcp_user_has_active_membership() && $user_id && $email && $subscription && $subscription_id;
	$form              = $show_form ? do_shortcode( '[gravityform id=' . $form_id . ' title=false description=false ajax=false field_values="user_id=' . $user_id . '&user_email=' . $email . '&subscription=' . $subscription . '&subscription_id=' . $subscription_id . '"]' ) : false;

	?>
		<div class="template-lead-gen-page-content-outer">
			<div class="wrap">
				<?php if ( $show_form ) : ?>
					<h2 style="margin-bottom: 2rem">Please Review and Agree to this License Agreement</h2>
					<div class="license-form">
						<?php echo $form; ?>
					</div>
				<?php endif; ?>
				<?php echo $license_agreement ? $license_agreement : ''; ?>
				<?php if ( $show_form ) : ?>
					<div class="license-form">
						<?php echo $form; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php
}


get_header();
do_action( 'objectiv_page_content' );
get_footer();
