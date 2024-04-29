<?php
/*
Template Name: Members Dashboard
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_loop', 'member_loop' );

function member_loop() {

	$prefix  = '_kitces_';
	$page_id = get_the_ID();
	$class   = '';
	global $CGD_CECredits;

	if ( have_posts() ) :
		while ( have_posts() ) :
			the_post();

			do_action( 'genesis_before_entry' );

			printf( '<article %s>', genesis_attr( 'entry' ) );

				do_action( 'genesis_entry_header' );

				do_action( 'genesis_before_entry_content' );
				printf( '<div %s>', genesis_attr( 'entry-content' ) );
			?>
				<div class="item-page members-dashboard">
					<div class="members-top-bottom-bar">
						<a href="<?php echo get_home_url(); ?>/member/my-account/" class="button button-blue">My Account Details</a>
						<a href="<?php echo get_home_url(); ?>/course-catalog" class="button button-blue">CE Catalog</a>
					</div>
					<div class="members-dashboard-grid">
						<?php if ( kitces_is_valid_student_member() ) : ?>
							<div class="member-product students">
								<i class="fas fa-book"></i>
								<h4>Kitces College</h4>
								<div class="member-product-actions">
										<a href="<?php echo get_home_url(); ?>/member/kitces-college/" class="button">Enter</a>
								</div>
							</div>
						<?php endif; ?>
						<div class="member-product quizzes">
							<i class="fas fa-pen-square"></i>
							<h4>Nerd's Eye View CE Quizzes</h4>
							<div class="member-product-actions">
								<?php if ( kitces_is_valid_premier_member() || kitces_is_valid_basic_member() ) : ?>
									<a href="<?php echo get_home_url(); ?>/member/nerds-eye-view-blog-ce-quizzes/" class="button">Take a Blog Quiz</a>
								<?php elseif ( kitces_is_valid_trial_member() ) : ?>
									<a href="<?php echo get_home_url(); ?>/member/trial-membership-ce-quiz/" class="button" onClick="__gaTracker('send', 'event', 'product', 'purchase', 'trial-quiz');">Take Your Sample Quiz Now!</a>
								<?php else : ?>
									<a href="<?php echo get_home_url(); ?>/become-a-member" class="button button-blue" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'newsletters-become-member-link');">Members Only - Get Access Now!</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="member-product newsletters <?php echo $class; ?>">
							<?php if ( ! kitces_is_valid_premier_member() ) : ?>
								<span class="members-only">For Premier Members Only</span>
							<?php endif; ?>
							<i class="fas fa-envelope-square"></i>
							<h4>Kitces Report Newsletters &amp; CE Quizzes</h4>
							<?php if ( kitces_is_valid_premier_member() ) : ?>
								<div class="member-product-actions two-buttons">
									<a href="<?php echo get_home_url(); ?>/member/newsletter-archives/" class="button button-small">Read Newsletters</a>
									<a href="<?php echo get_home_url(); ?>/member/ce-quizzes/" class="button button-small">Take a Newsletter Quiz</a>
								</div>
							<?php else : ?>
								<div class="member-product-actions">
									<a href="<?php echo get_home_url(); ?>/become-a-member" class="button button-blue" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'newsletters-become-member-link');">Premier Members Only - Get Access Now!</a>
								</div>
							<?php endif; ?>
						</div>
						<div class="member-product webinars <?php echo $class; ?>">
							<i class="fas fa-desktop"></i>
							<h4>Kitces Webinars</h4>
							<div class="member-product-actions">
								<?php if ( kitces_is_valid_premier_member() || kitces_is_valid_basic_member() ) : ?>
									<a href="http://www.kitces.com/webinars" class="button">View Webinars</a>
								<?php else : ?>
									<a href="<?php echo get_home_url(); ?>/become-a-member" class="button button-blue" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'webinars-become-member-link');">Members Only - Get Access Now!</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="member-product resources <?php echo $class; ?>">
							<?php if ( ! kitces_is_valid_premier_member() ) : ?>
								<span class="members-only">For Premier Members Only</span>
							<?php endif; ?>
							<i class="fas fa-briefcase"></i>
							<h4>Member Resources</h4>
							<div class="member-product-actions">
								<?php if ( kitces_is_valid_premier_member() ) : ?>
									<a href="<?php echo get_home_url(); ?>/kitces-members-section-resources/" class="button">Practice Management</a>
									<a href="<?php echo get_home_url(); ?>/member/graphics-library/" class="button">Graphics Library</a>
								<?php else : ?>
									<a href="<?php echo get_home_url(); ?>/become-a-member" class="button button-blue" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'resources-become-member-link');">Premier Members Only - Get Access Now!</a>
								<?php endif; ?>
							</div>
						</div>
						<div class="member-product office-hours <?php echo $class; ?>">
							<?php if ( ! kitces_is_valid_premier_member() ) : ?>
								<span class="members-only">For Premier Members Only</span>
							<?php endif; ?>
							<i class="fas fa-user-circle"></i>
							<h4>Office Hours</h4>
							<div class="member-product-actions">
								<?php if ( kitces_is_valid_premier_member() ) : ?>
									<a href="<?php echo get_home_url(); ?>/member/office-hours" class="button">View Office Hours</a>
								<?php else : ?>
									<a href="<?php echo get_home_url(); ?>/become-a-member" class="button button-blue" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'office-hours-become-member-link');">Premier Members Only - Get Access Now!</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
				<?php
				echo '</div>'; // * end .entry-content
				do_action( 'genesis_after_entry_content' );

				do_action( 'genesis_entry_footer' );

				echo '</article>';

				do_action( 'genesis_after_entry' );

		endwhile; // * end of one post
		do_action( 'genesis_after_endwhile' );

	else : // * if no posts exist
		do_action( 'genesis_loop_else' );
	endif; // * end loop
}

genesis();
