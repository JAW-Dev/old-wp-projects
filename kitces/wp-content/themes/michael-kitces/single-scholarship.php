<?php

// Single page for scholarships

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_after_header', 'objectiv_intro_header' );
add_action( 'genesis_after_header', 'objectiv_single_scholarship_details' );

function objectiv_intro_header() { ?>
	<?php
	$schol_list_page = mk_get_field( 'scholarships_list_page', 'scholarships-settings' );
	$title           = mk_get_field( 'page_title_override', $conf_page );
	$sub_title       = mk_get_field( 'page_sub_title', $conf_page );
	?>
	<section class="page-section spt spb bg-light-blue">
		<div class="wrap">
			<div class="head-content tac mw-800 mlra">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="head-title wt fwb f48 no-underline"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p class="head-sub-title wt fwm f26"><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_single_scholarship_details() {
	global $post;
	$list_block_settings = mk_get_scholarship_list_block_settings();
	?>
		<section class="page-section spt spb conference-list-section">
			<div class="wrap mw-1024">
				<div class="mk-list-block">
					<?php mk_list_block_inner( $post->ID, $list_block_settings ); ?>
				</div>
			</div>
		</section>
	<?php
}

genesis();
