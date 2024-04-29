<?php

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_single_download_content' );
function objectiv_single_download_content() { ?>
	<div class="single-download-content-outer">
		<?php
		objectiv_top_section();
		objectiv_return_section();
		?>
	</div>
<?php

}

function objectiv_top_section() {
	$init_img           = get_field( 'initial_image' );
	$pop_image          = get_field( 'pop_image' );
	$description        = get_field( 'download_description' );
	$enable_dynamic_pdf = get_field( 'enable_dynamic_pdf' );
	$svg_downloads      = get_field( 'svg_downloads' );
	$d_buttons          = get_field( 'download_buttons' );
	$resources          = get_field( 'additional_resources' );
	$unlocked           = false;
	$mem_page           = get_field( 'become_member_page', 'option' );
	$sample_page        = get_field( 'view_sample_page', 'option' );
	$blurred_img        = get_field( 'blurred_flowchart_holder_image', 'option' );
	$non_member_buttons = array();

	if ( ! empty( $mem_page ) ) {
		$mem_perm = array(
			'url'    => get_permalink( $mem_page->ID ),
			'target' => '',
			'title'  => 'Become a Member',
		);

		array_push( $non_member_buttons, $mem_perm );
	}

	if ( ! empty( $sample_page ) ) {
		$sam_perm = array(
			'url'    => get_permalink( $sample_page->ID ),
			'target' => '',
			'title'  => 'View Sample',
		);

		array_push( $non_member_buttons, $sam_perm );
	}

	global $genesis_simple_share;
	$share = @genesis_share_get_icon_output( 'after-entry', $genesis_simple_share->icons );

	if ( ! empty( $init_img ) ) {
		$image_id   = $init_img['ID'];
		$disp_image = wp_get_attachment_image( $image_id, 'obj_fifty_cont', false, array( 'class' => 'fifty-image-content__image' ) );
	}

	if ( ! empty( $blurred_img ) ) {
		$blurred_image_id = $blurred_img['ID'];
		$blurred_img      = wp_get_attachment_image( $blurred_image_id, 'obj_fifty_cont', false, array( 'class' => 'fifty-image-content__image' ) );
	}

	if ( ! empty( $pop_image ) ) {
		$pop_image = $pop_image['url'];
	}

	if ( rcp_user_can_access( get_current_user_id(), get_the_ID() ) ) {
		$unlocked = true;
	}

	?>
	<section class="single-download-top sectionmt sectionmb">
		<div class="wrap">
		<div class="single-download-top__inner">
			<div class="single-download-top__left">
				<div class="fifty-image-content__image-wrap">
					<?php
					if ( $unlocked ) {
						obj_display_pop_img( $pop_image, $disp_image );
					} else {
						obj_display_pop_img( null, $blurred_img, true );
					}
					?>
				</div>
        <?php
				if ( $unlocked ) {
					if ( $enable_dynamic_pdf ) {
						obj_do_dynamic_download_buttons( $svg_downloads );
					} else {
						obj_do_download_buttons( $d_buttons );
					}
				} else {
					obj_do_non_member_download_buttons( $non_member_buttons );
				}
				?>
			</div>
			<div class="single-download-top__right">
				<?php if ( ! empty( $description ) ) : ?>
					<div class="single-download__description">
						<?php echo $description; ?>
					</div>
				<?php endif; ?>
				<?php
				if ( $unlocked ) {
					objectiv_download_additional_resources( $resources );
				}
				?>
				<div class="single-download__share-wrap">
					<div class="single-download__share-title">
						Share:
					</div>
					<div class="single-download__share-buttons">
						<?php echo $share; ?>
					</div>
				</div>
			</div>
		</div>
		</div>
	</section>
	<?php

}

function objectiv_return_section() {
	$back_link = get_post_type_archive_link( 'download' );
	if ( ! empty( $back_link ) ) {
	?>
	<section class="single-download-return sectionmt sectionmb">
		<div class="wrap tac">
			<span class="button">
				<a href="<?php echo esc_url( $back_link ); ?>">Back to Member Section</a>
			</span>
		</div>
	</section>
	<?php
	}
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
