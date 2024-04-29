<?php

/*
Template Name: Lead Gen
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'template-lead-gen';
	return $classes;

}

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_lead_gen_page_content' );
function objectiv_lead_gen_page_content() { ?>
	<div class="template-lead-gen-page-content-outer">
		<?php
			objectiv_top_section();
			objectiv_two_section();
		?>
	</div>
<?php
}

function objectiv_top_section() {
	$init_img   = get_field( 'lg_initial_image' );
	$pop_img    = get_field( 'lg_pop_up_image' );
	$blurb      = get_field( 'lb_first_section_blurb' );
	$form_title = get_field( 'lb_title_above_form' );
	$form       = get_field( 'lb_form_to_display' );

	if ( ! empty( $init_img ) ) {
		$image_id   = $init_img['ID'];
		$disp_image = wp_get_attachment_image( $image_id, 'obj_fifty_cont', false, array( 'class' => 'fifty-image-content__image' ) );
	}

	if ( ! empty( $pop_img ) ) {
		$pop_img = $pop_img['url'];
	}

	?>
	<section class="lead-gen-top sectionmt sectionmb">
		<div class="wrap">
			<div class="lead-gen-top__inner">
				<div class="lead-gen-top__first">
					<div class="fifty-image-content__image-wrap">
						<?php obj_display_pop_img( $pop_img, $disp_image ); ?>
					</div>
				</div>
				<div class="lead-gen-top__second">
					<?php if ( ! empty( $blurb ) ) : ?>
						<div class="lead-gen-top__blurb lmb0"><?php echo $blurb; ?></div>
					<?php endif; ?>
					<?php if ( ! empty( $form ) ) : ?>
						<?php if ( ! empty( $form_title ) ) : ?>
							<h3 class="lead-gen-top__form-title"><?php echo esc_html( $form_title ); ?></h3>
						<?php endif; ?>
						<?php
							gravity_form_enqueue_scripts( $form['id'], true );
							gravity_form( $form['id'], false, false, false, '', false, 1 );
						?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_two_section() {
	$title   = get_field( 'lb_2_section_title' );
	$blurb   = get_field( 'lb_2_section_blurb' );
	$n_items = get_field( 'lb_2_numbered_items' );

	?>
	<section class="lead-gen-items sectionpt sectionpb">
		<div class="wrap">
			<div class="lead-gen-items__inner">
				<?php obj_section_header( $title ); ?>
				<?php if ( ! empty( $blurb ) ) : ?>
					<p class="mb0 lead-gen-items__blurb"><?php echo esc_html( $blurb ); ?></p>
				<?php endif; ?>
				<?php
				if ( ! empty( $n_items ) ) :
					objectiv_do_numbered_item_list( $n_items );
				endif;
				?>
			</div>
		</div>
	</section>
	<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
