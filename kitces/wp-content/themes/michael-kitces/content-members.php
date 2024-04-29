<?php
/**
* Template Name: Members Dashboard Template
*/

remove_action( 'genesis_loop', 'genesis_do_loop' );

?>


<?php
/*
Template Name: Members Dashboard
*/

$prefix = '_kitces_';
$page_id = get_the_ID();
global $CGD_CECredits;
?>
	<div class="row-fluid">
		<div class="span12">
			<div class="container maincontent nofauxsidebar">
                <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
					<div class="item-page members-dashboard">
						<h1 class="member-dashboard-title"><?php the_title(); ?></h1>
						<?php if( kitces_is_valid_premier_member() || kitces_is_valid_basic_member() ): ?>
							<h3 class="member-dashboard-quiz-title">Your Currently Remaining CFP CE Quizzes are: <span class="members-label">Unlimited</span></h3>
						<?php elseif ( kitces_is_valid_trial_member() ): ?>
							<h3 class="member-dashboard-quiz-title">You have 1 trial quiz available.</span></h3>
						<?php endif; ?>
                        <div class="members-dashboard-grid">
							<div class="row-fluid">
                                <div class="members-product quizzes span6">
                                    <div class="members-product-image">
                                        <h4>Nerd's Eye View CE Quizzes</h4>
                                    </div>
                                    <div class="members-product-action">
										<?php if( kitces_is_valid_trial_member() ): ?>
											<a href="<?php echo get_home_url(); ?>/member/trial-membership-ce-quiz/" class="btn btn-primary" onClick="__gaTracker('send', 'event', 'product', 'purchase', 'trial-quiz');">Take Your Sample Quiz Now!</a>
										<?php else: ?>
											<a href="<?php echo get_home_url(); ?>/member/nerds-eye-view-blog-ce-quizzes/" class="btn btn-primary">Take a Blog Quiz</a>
										<?php endif; ?>
                                    </div>
                                </div>
                                <div class="members-product newsletters span6">
                                    <div class="members-product-image">
										<?php if ( ! kitces_is_valid_premier_member() ): ?>
											<div class="members-only-badge">
												<p>Members Only</p>
											</div>
										<?php endif; ?>
                                        <h4>Kitces Report Newsletters </br>&amp; CE Quizzes</h4>
                                    </div>
                                    <div class="members-product-action">
										<?php if( kitces_is_valid_premier_member() ): ?>
	                                        <a style="width: 42%; display: inline-block;" href="<?php echo get_home_url(); ?>/member/newsletter-archives/" class="btn btn-primary">Read Newsletters</a>
											<a style="width: 42%; display: inline-block;" href="<?php echo get_home_url(); ?>/member/ce-quizzes/" class="btn btn-primary">Take a Newsletter Quiz</a>
										<?php else: ?>
											<a href="<?php echo get_home_url(); ?>/become-a-member" class="btn btn-primary" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'newsletters-become-member-link');">For Members Only - Get Access Now!</a>
										<?php endif; ?>
                                    </div>
                                </div>
							</div>
							<div class="row-fluid">
                                <div class="members-product webinars span6">
                                    <div class="members-product-image">
										<?php if ( !kitces_is_valid_premier_member() ): ?>
											<div class="members-only-badge">
												<p>Members Only</p>
											</div>
										<?php endif; ?>
                                        <h4>Kitces Webinars </br>&amp;amp; CE Quizzes</h4>
                                    </div>
                                    <div class="members-product-action">
										<?php if( kitces_is_valid_premier_member() ): ?>
	                                        <a href="<?php echo get_home_url(); ?>/webinars" class="btn btn-primary">View Webinars</a>
										<?php else: ?>
											<a href="<?php echo get_home_url(); ?>/become-a-member" class="btn btn-primary" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'webinars-become-member-link');">For Members Only - Get Access Now!</a>
										<?php endif; ?>
                                    </div>
                                </div>
                                <div class="members-product resources span6">
                                    <div class="members-product-image">
										<?php if ( ! kitces_is_valid_premier_member() ): ?>
											<div class="members-only-badge">
												<p>Members Only</p>
											</div>
										<?php endif; ?>
                                        <h4>Member Resources</h4>
                                    </div>
                                    <div class="members-product-action">
										<?php if( kitces_is_valid_premier_member() ): ?>
	                                        <a href="<?php echo get_home_url(); ?>/kitces-members-section-resources/" class="btn btn-primary">Access Resources</a>
										<?php else: ?>
											<a href="<?php echo get_home_url(); ?>/become-a-member" class="btn btn-primary" onClick="__gaTracker('send', 'event', 'product', 'pre-purchase', 'resources-become-member-link');">For Members Only - Get Access Now!</a>
										<?php endif; ?>
                                    </div>
                                </div>
							</div>
                        </div>
						<!-- <?php the_content(); ?> -->
					</div>
                <?php endwhile; ?>
                <?php endif; ?>
			</div><!--END maincontent container-->
		</div><!--END span12-->
	</div><!--END row-fluid-->

<?php
genesis();


?>
