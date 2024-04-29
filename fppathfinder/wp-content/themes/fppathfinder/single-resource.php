<?php

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_single_download_content' );
function objectiv_single_download_content() {
	?>
	<div class="single-download-content-outer">
		<?php objectiv_top_section(); ?>
	</div>
	<?php
}

/**
 * FP Download Button
 *
 * Given an array with 'button_text' and 'download' keys, echo out a button.
 * The reason we pass in an index is because when this project was first built the dev manually passed in which buttons were red and which weren't, instead of using css selectors to determine this. In the interest of timeliness I'm forgoing refactoring that in favor of just making it work. If you're still reading at this point congrats cause I wouldn't have.
 *
 * @param array $details array( 'button_text' => ..., 'download' => \WP_Post )
 * @param int   $index   This items index in it's array.
 */
function fp_download_button( array $details, int $index ) {

	if ( ! empty( $details['button_text'] ) && $details['download'] instanceof \WP_Post ) {
		$download_id = $details['download']->ID;
		$download    = new \FP_PDF_Generator\Download( $download_id );
		$href        = $download->get_download_url();
		$is_red      = 0 === $index;
		$class       = $is_red ? 'red-button' : 'button';

		echo fp_get_link_button_no_follow( $href, '', $details['button_text'], $class, false, true );
	}
}


/**
 * FP Interactive Checklist Button
 *
 * Given an array with 'button_text' and 'download' keys, echo out a button for an interactive checklist.
 *
 * @param array $details array( 'button_text' => ..., 'download' => \WP_Post )
 */
function fp_interactive_checklist_button( array $details ) {

	if ( empty( $details['button_text'] ) || ! $details['interactive_checklist'] instanceof \WP_Post ) {
		return;
	}

	$checklist_id = $details['interactive_checklist']->ID;
	$can_access   = rcp_user_can_access( get_current_user_id(), $checklist_id );
	$href         = get_permalink( $checklist_id );
    $class        = 'green-button button ' . ( $can_access ? '' : 'disabled-checklist-button' );
    $inner_class = null;

    if ( ! $can_access ) {
        $href = fp_get_upsell_modal_link( 'interactive' );
        $inner_class .= ' upsell-modal';
    }

	ob_start();
	obj_svg( 'pad-locked' );
	$lock = ob_get_clean();

	echo fp_get_link_button_no_follow( $href, '_blank', $details['button_text'] . ( $can_access ? '' : $lock ), $class, false, false, $inner_class );
}

function objectiv_top_section() {
	$image                         = get_field( 'image' );
	$modal_image                   = get_field( 'zoom_image' );
	$description                   = get_field( 'description' );
	$download_button_details       = get_field( 'download_buttons' );
	$interactive_checklist_details = get_field( 'interactive_checklist_buttons' );
	$become_a_member_page          = get_field( 'become_member_page', 'option' );
	$sample_page                   = get_field( 'view_sample_page', 'option' );
	$become_a_member_page_href     = $become_a_member_page ? get_permalink( $become_a_member_page->ID ) : false;
	$sample_page_href              = $sample_page ? get_permalink( $sample_page->ID ) : false;
	$blurred_image_field           = get_field( 'blurred_flowchart_holder_image', 'option' );
	$blurred_image_html            = empty( $blurred_image_field ) ? '' : wp_get_attachment_image( $blurred_image_field['ID'], 'obj_fifty_cont', false, array( 'class' => 'fifty-image-content__image' ) );
	$unlocked                      = rcp_user_can_access( get_current_user_id(), get_the_ID() );

	global $genesis_simple_share;
	//phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
	$share = @genesis_share_get_icon_output( 'after-entry', $genesis_simple_share->icons );

	if ( ! empty( $image ) ) {
		$image_id   = $image['ID'];
		$disp_image = wp_get_attachment_image( $image_id, 'obj_fifty_cont', false, array( 'class' => 'fifty-image-content__image' ) );
	}

	?>
	<section class="single-download-top sectionmt sectionmb">
		<div class="wrap">
		<div class="single-download-top__inner">
			<div class="single-download-top__left">
				<div class="fifty-image-content__image-wrap">
					<?php
					if ( $unlocked ) {
						obj_display_pop_img( $modal_image['url'], $disp_image );
					} else {
						obj_display_pop_img( null, $blurred_image_html, true );
					}
					?>
				</div>
				<div class="download-buttons-grid">
					<?php do_action( 'obj_before_download_buttons' ); ?>
					<?php
					if ( $unlocked ) {
						array_map( 'fp_download_button', $download_button_details, array_keys( $download_button_details ) );
					} else {
						if ( $become_a_member_page_href ) {
							echo fp_get_link_button( $become_a_member_page_href, '', 'Become a Member', 'red-button' );
						}
						if ( $sample_page_href ) {
							echo fp_get_link_button( $sample_page_href, '', 'View Sample' );
						}
					}
					?>
					<?php if ( $interactive_checklist_details ) : ?>
						<?php
						foreach ( $interactive_checklist_details as $checklist ) {
							fp_interactive_checklist_button( $checklist );
						}
						?>
					<?php endif; ?>
					<?php do_action( 'obj_after_download_buttons' ); ?>
				</div>
			</div>
			<div class="single-download-top__right">
				<?php do_action( 'obj_before_resource_description' ); ?>
				<?php if ( ! empty( $description ) ) : ?>
					<div class="single-download__description"><?php echo $description; ?></div>
				<?php endif; ?>
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
