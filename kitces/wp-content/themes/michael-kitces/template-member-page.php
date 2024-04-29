<?php
/*
Template Name: Members Page
*/

add_filter( 'body_class', 'obj_member_dash_body_class' );
function obj_member_dash_body_class( $classes ) {
	$classes[] = 'member-page';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'member_loop_2' );

function member_loop_2() {
	$current_user                      = wp_get_current_user();
	$display_welcome                   = mk_get_field( 'display_welcome' );
	$pre_name_text                     = mk_get_field( 'pre_name_text' );
	$display_page_title_member_details = mk_get_field( 'display_page_title_member_details' );

	$expiration_details = mk_get_customer_subscription_expiration( 'F j, Y' );
	$user_expiration    = mk_key_value( $expiration_details, 'user_expiration' );
	$expire_date_title  = mk_key_value( $expiration_details, 'expire_date_title' );

	$membership_level_details = mk_get_current_user_membership_level_details();
	$membership_level_label   = mk_key_value( $membership_level_details, 'label' );
	$membership_level_title   = mk_key_value( $membership_level_details, 'level' );
	$is_basic_member          = kitces_is_valid_basic_member();
	$is_premier_member        = kitces_is_valid_premier_member();
	$is_student_member        = kitces_is_valid_student_member();
	$is_reader_member         = kitces_is_valid_reader_member();
	$is_a_member              = $is_basic_member || $is_premier_member || $is_student_member || $is_reader_member;
	$has_member_nav           = has_nav_menu( 'member_sidebar_prem' ) || has_nav_menu( 'member_sidebar_bas' ) || has_nav_menu( 'member_sidebar_stud' ) || has_nav_menu( 'member_sidebar_read' );
	$show_menu                = $is_a_member && $has_member_nav;

	$sidebar_class = '';
	if ( isset( $_COOKIE['member-side-bar-hidden'] ) ) {
		$sidebar_class = 'side-bar-hidden';
	}

	?>
	<?php if ( ! empty( $current_user ) ) : ?>
		<section class="member-template-inner">

			<div class="member-sidebar <?php echo $sidebar_class; ?>">
				<?php if ( $show_menu ) : ?>
					<div class="member-sidebar-toggle">Member Quick Nav</div>
				<?php endif; ?>

				<div class="member-sidebar-inner">
						<?php if ( $show_menu ) : ?>
							<!-- Outputs member sidebar menu -->
							<div class="member-sidebar-menu-wrap">
								<?php if ( $is_basic_member && has_nav_menu( 'member_sidebar_bas' ) ) : ?>
									<?php wp_nav_menu( array( 'theme_location' => 'member_sidebar_bas' ) ); ?>
								<?php elseif ( $is_premier_member && has_nav_menu( 'member_sidebar_prem' ) ) : ?>
									<?php wp_nav_menu( array( 'theme_location' => 'member_sidebar_prem' ) ); ?>
								<?php elseif ( $is_student_member && has_nav_menu( 'member_sidebar_stud' ) ) : ?>
									<?php wp_nav_menu( array( 'theme_location' => 'member_sidebar_stud' ) ); ?>
								<?php elseif ( $is_reader_member && has_nav_menu( 'member_sidebar_read' ) ) : ?>
									<?php wp_nav_menu( array( 'theme_location' => 'member_sidebar_read' ) ); ?>
								<?php endif; ?>
							</div>
						<?php endif; ?>

					<!-- Output details about users account -->
					<div class="member-sidebar-details">

						<?php if ( ! empty( $expiration_details ) && is_array( $expiration_details ) ) : ?>
							<div class="payment-details">
								<?php if ( ! empty( $expire_date_title ) ) : ?>
									<div class="expire-title"><?php echo $expire_date_title; ?></div>
								<?php endif; ?>
								<?php if ( ! empty( $user_expiration ) ) : ?>
									<div class="expire-date"><?php echo $user_expiration; ?></div>
								<?php endif; ?>
							</div>
						<?php endif; ?>

						<div class="account-button-wrap">
							<a href="<?php echo get_home_url(); ?>/member/my-account/" class="button button-blue block">My Account</a>
						</div>

					</div>

				</div>


			</div>

			<div class="member-content">

				<div class="page-title">
					<div class="left-side">
						<button class="member-sidebar-toggle-width <?php echo $sidebar_class; ?>" title="Toggle Sidebar Open/Closed">
							<svg xmlns="http://www.w3.org/2000/svg" width="24px" fill="none" viewBox="0 0 24 24" stroke="currentColor">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
							</svg>
						</button>
						<?php if ( ! empty( $current_user ) && $display_welcome && ! empty( $pre_name_text ) ) : ?>
							<h1 class=""><?php echo wp_kses_post( $pre_name_text ); ?> <?php echo wp_kses_post( $current_user->user_firstname ); ?></h1>
						<?php else : ?>
							<h1 class=""><?php echo wp_kses_post( get_the_title() ); ?></h1>
						<?php endif; ?>
					</div>

					<?php if ( $display_page_title_member_details && ! empty( $membership_level_title ) && ! empty( $membership_level_label ) ) : ?>
						<div class="membership-level">
							<span class="label"><?php echo $membership_level_label; ?> </span><span class="level"><?php echo $membership_level_title; ?></span>
						</div>
					<?php endif; ?>
				</div>

				<div class="page-content">
					<?php genesis_do_loop(); ?>
				</div>

			</div>


		</section>
		<?php
	endif;

	if ( class_exists( 'Kitces\Includes\Classes\User\PasswordModal' ) ) {
		( new Kitces\Includes\Classes\User\PasswordModal() )->render();
	}
}


genesis();
