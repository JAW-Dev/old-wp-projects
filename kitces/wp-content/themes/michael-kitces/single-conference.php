<?php

add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
add_action( 'genesis_after_header', 'objectiv_intro_header' );
add_action( 'genesis_after_header', 'objectiv_conference_content' );

function objectiv_intro_header() { ?>
	<?php
	$conf_page = mk_get_field( 'conferences_page', 'option' );
	$title     = mk_get_field( 'page_title_override', $conf_page );
	$sub_title = mk_get_field( 'page_sub_title', $conf_page );
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

function objectiv_conference_content() {
	global $post;
	$conference = get_single_conference_details( $post->ID );
	?>
		<?php if ( ! empty( $conference ) ) : ?>
		<section class="page-section spt spb conference-list-section">
			<div class="wrap mw-1024">
				<?php do_conference_block( $conference, true ); ?>
			</div>
		</section>
		<?php endif; ?>
	<?php
}

function objectiv_conferences_meta_tags() {
	global $post;
	$conference = get_single_conference_details( $post->ID );
	if ( ! empty( $conference ) ) {
		$content = mk_acf_get_field( 'description' );
		echo '<meta name="description" content="' . $content . '" />';
	}
}

add_action( 'wp_head', 'objectiv_conferences_meta_tags' );

genesis();
