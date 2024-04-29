<?php

/*
Template Name: Lead Thanks
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'template-lead-thanks';
	return $classes;

}

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'objectiv_lead_gen_page_content' );
function objectiv_lead_gen_page_content() { ?>
	<div class="template-lead-gen-page-content-outer">
		<?php
			objectiv_top_section();
		?>
	</div>
<?php
}

function objectiv_top_section() {
	$title     = get_field( 'defaults_title' );
	$sub_title = get_field( 'defaults_sub_title' );
	$message   = get_field( 'defaults_message' );
	$overrides = get_field( 'overrides' );
	$form_id   = $_GET['form_id'];
	$matched   = false;

	if ( ! empty( $overrides ) && ! empty( $form_id ) ) {
		$form_id = (int) $form_id;
		foreach ( $overrides as $over ) {
			if ( ! $matched ) {
				if ( $over['referring_form']['id'] === $form_id ) {
					$title     = $over['title'];
					$sub_title = $over['sub_title'];
					$message   = $over['message'];
					$matched   = true;
				}
			}
		}
	}

	?>
	<section class="lead-gen-thanks-top sectionmt sectionmb">
		<div class="wrap">
			<div class="lead-gen-thanks-top__inner">
			<?php if ( ! empty( $title ) ) : ?>
				<h1 class="lead-gen-thanks__title"><?php echo esc_html( $title ); ?></h1>
			<?php endif; ?>
			<?php if ( ! empty( $sub_title ) ) : ?>
				<h5 class="lead-gen-thanks__sub-title"><?php echo esc_html( $sub_title ); ?></h5>
			<?php endif; ?>
			<?php if ( ! empty( $message ) ) : ?>
				<div class="lead-gen-thanks__message lmb0">
					<?php echo $message; ?>
				</div>
			<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
