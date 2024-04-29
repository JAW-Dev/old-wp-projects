<?php

/*
Template Name: Start Here Page 2.0
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

    $classes[] = 'start-here-2';
    return $classes;

}
// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_after_header', 'cgd_start_here_nav_section' );
function cgd_start_here_nav_section() { ?>
    <?php
	$prefix = '_cgd_';
	$icons = get_post_meta( get_the_ID(), $prefix . 'icon_group_2', true );
	?>
	<section class="content-section start-here-nav">
		<div class="wrap">
			<div class="bracket advisors">
				<h4 class="bracket-title">Financial Advisors</h4>
				<span class="bracket-divider">
				</span>
			</div>
			<div class="start-here-nav-blocks">
				<?php foreach( $icons as $icon ): ?>
					<div class="start-here-nav-block">
						<i class="fas <?php echo $icon['icon']; ?>"></i>
						<h2 class="start-here-nav-block-title"><?php echo $icon['title']; ?></h2>
					</div>
				<?php endforeach; ?>
			</div>
			<div class="bracket consumers">
				<span class="bracket-divider">
				</span>
				<h4 class="bracket-title">Consumers</h4>
			</div>
			<div class="bracket vendors">
				<span class="bracket-divider">
				</span>
				<h4 class="bracket-title">Vendors Who Serve Financial Advisors</h4>
			</div>
		</div>
	</section>
<?php }


genesis();
