<?php

/*
Template Name: Product Landing Page
*/

add_filter( 'body_class', 'objectiv_product_landing_body_class' );
function objectiv_product_landing_body_class( $classes ) {
	$classes[] = 'product-landing';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_action( 'genesis_after_header', 'objectiv_product_landing_page_content' );

function objectiv_product_landing_page_content() {
	// Output the header
	objectiv_product_landing_hero();
	objectiv_product_first_content();
	objectiv_product_second_content();
	objectiv_product_third_content();
	objectiv_product_purchase_cta();
}

function objectiv_product_landing_hero() {
	$title     = get_field( 'page_title_override' );
	$sub_title = get_field( 'page_sub_title' );
	$image_id  = get_field( 'page_hero_image' );
	$image     = '';

	if ( empty( $title ) ) {
		$title = get_the_title();
	}

	if ( empty( $image_id ) ) {
		$image_id = get_post_thumbnail_id();
	}

	if ( ! empty( $image_id ) ) {
		$image = wp_get_attachment_image( $image_id, 'large', false, array( 'class' => 'product-landing-hero__image' ) );
	}

	if ( ! empty( $title ) ) { ?>
		<section class="product-landing-hero spt-l spb-l tac text-white bg-light-blue">
			<div class="wrap">
				<?php if ( ! empty( $image ) ) : ?>
					<?php echo $image; ?>
				<?php endif; ?>
				<h1 class="product-landing-hero__title f48 mw-800 mlra mb0 fwb"><?php echo $title; ?></h1>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<h3 class="product-landing-hero__sub-title f32 mb0"><?php echo $sub_title; ?></h3>
				<?php endif; ?>
			</div>
		</section>
	<?php
	}
}

function objectiv_product_first_content() {
	$sec_title   = get_field( 'first_content_title' );
	$sec_content = get_field( 'first_content' );
	$logos       = get_field( 'first_section_logos' );

	if ( ! empty( $sec_title ) && ! empty( $sec_content ) ) {
	?>
		<section class="product-landing-content spt-l spb-l bg-white">
			<div class="wrap mw-800">
				<h2 class="section-title tac f36 fwb"><?php echo $sec_title; ?></h2>
				<div class="product-landing-content__content lmb0"><?php echo $sec_content; ?></div>
			</div>
			<div class="wrap">
				<?php if ( ! empty( $logos ) ) : ?>
					<div class="product-landing-content__logos">
						<?php foreach ( $logos as $l ) : ?>
							<img src="<?php echo $l['url']; ?>" alt="<?php echo $l['alt']; ?>">
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php
	}
}

function objectiv_product_second_content() {
	$sec_title   = get_field( 'second_content_title' );
	$sec_content = get_field( 'second_content' );

	if ( ! empty( $sec_title ) && ! empty( $sec_content ) ) {
		?>
		<section class="product-landing-content spt-l spb-l bg-light-gray">
			<div class="wrap mw-800">
				<h2 class="section-title tac f36 fwb"><?php echo $sec_title; ?></h2>
				<div class="product-landing-content__content lmb0"><?php echo $sec_content; ?></div>
			</div>
		</section>
	<?php

	}
}

function objectiv_product_third_content() {
	$sec_title   = get_field( 'third_content_title' );
	$sec_content = get_field( 'third_content' );

	if ( ! empty( $sec_title ) && ! empty( $sec_content ) ) {
		?>
		<section class="product-landing-content spt-l spb-l bg-white">
			<div class="wrap mw-800">
				<h2 class="section-title tac f36 fwb"><?php echo $sec_title; ?></h2>
				<div class="product-landing-content__content lmb0"><?php echo $sec_content; ?></div>
			</div>
		</section>
	<?php

	}
}

function objectiv_product_purchase_cta() {
	$cta_title = get_field( 'purchase_cta_title' );
	$cta_blurb = get_field( 'purchase_cta_blurb' );
	$cta_price = get_field( 'purchase_cta_price_text' );
	$cta_btn   = get_field( 'purchase_cta_button_details' );
	$cta_foot  = get_field( 'purchase_cta_cta_footer_text' );

	if ( ! empty( $cta_title ) && ! empty( $cta_btn ) ) {
		?>
		<section class="product-landing-cta spt-l spb-l bg-light-blue text-white tac">
			<div class="wrap mw-800">
				<h2 class="product-landing-cta__title f36 fwb mb0"><?php echo $cta_title; ?></h2>
				<?php if ( ! empty( $cta_blurb ) ) : ?>
					<div class="product-landing-cta__blurb lmb0 mb0 mt1"><?php echo $cta_blurb; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $cta_price ) ) : ?>
					<div class="product-landing-cta__price f28"><?php echo $cta_price; ?></div>
				<?php endif; ?>
				<?php echo mk_link_html( $cta_btn, 'button large-button' ); ?>
				<?php if ( ! empty( $cta_foot ) ) : ?>
					<div class="product-landing-cta__foot mb0 lmb0"><?php echo $cta_foot; ?></div>
				<?php endif; ?>
			</div>
		</section>
	<?php

	}
}

genesis();
