<?php

/*
Template Name: Media Page
*/

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'media';
	return $classes;

}

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_after_header', 'cgd_media_info' );
function cgd_media_info() { ?>
	<?php
	$prefix = '_cgd_';
	$title = get_post_meta( get_the_ID(), $prefix . 'media_info_title', true );
	$desc = wpautop( get_post_meta( get_the_ID(), $prefix . 'media_info_desc', true ) );
	$appearances_title = get_post_meta( get_the_ID(), $prefix . 'media_appearances_title', true );
	$appearance_logos = get_post_meta( get_the_ID(), $prefix . 'media_logos_group', true );
	$appearance_button_text = get_post_meta( get_the_ID(), $prefix . 'media_appearances_button_text', true );
	$appearance_button_link = get_post_meta( get_the_ID(), $prefix . 'media_appearances_button_link', true );
	?>
    <section class="content-section media-info">
		<div class="wrap">
			<div class="one-half first">
				<h2><?php echo $title; ?></h2>
				<?php echo $desc; ?>
			</div>
			<div class="one-half media-logos">
				<h2><?php echo $appearances_title; ?></h2>
				<p>
					<?php foreach( $appearance_logos as $logo ): ?>
						<?php $alt = get_post_meta( $logo['logo_id'], '_wp_attachment_image_alt', true ); ?>
						<a href="<?php echo $logo['link']; ?>"><img src="<?php echo $logo['logo']; ?>" alt="<?php echo $alt; ?>" /></a>
					<?php endforeach; ?>
				</p>
				<?php if ( ! empty( $appearance_button_text ) && ! empty( $appearance_button_link ) ): ?>
					<a href="<?php echo $appearance_button_link; ?>" class="button"><?php echo $appearance_button_text; ?></a>
				<?php endif; ?>
			</div>
		</div>
	</section>
<?php }

add_action( 'genesis_after_header', 'cgd_nav_icons' );
function cgd_nav_icons() { ?>
	<?php
	$prefix = '_cgd_';
	$icons = get_post_meta( get_the_ID(), $prefix . 'media_icon_group', true );
	$count = 1;
	?>
    <section class="content-section media-nav">
		<div class="wrap">
			<?php foreach( $icons as $icon ): ?>
				<div class="nav-block">
					<a href="<?php echo $icon['link'] ?>">
						<span class="fa-stack fa-lg">
							<i class="fas fa-circle fa-stack-2x"></i>
							<i class="fas <?php echo $icon['icon']; ?> fa-stack-1x fa-inverse"></i>
						</span>
					</a>
					<p class="nav-block-title"><?php echo $icon['title']; ?></p>
				</div>
				<?php $count++; ?>
			<?php endforeach; ?>
		</div>
	</section>
<?php }

add_action( 'genesis_after_header', 'cgd_media_expertise' );
function cgd_media_expertise() { ?>
	<?php
	$prefix = '_cgd_';
	$title = get_post_meta( get_the_ID(), $prefix . 'expertise_title', true );
	$desc = wpautop( get_post_meta( get_the_ID(), $prefix . 'expertise_desc', true ) );
	?>
    <section id="expertise" class="content-section media-expertise">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $title; ?></h2>
			<div class="section-content-desc">
				<?php echo $desc; ?>
			</div>
		</div>
	</section>
<?php }

add_action( 'genesis_after_header', 'cgd_media_attribution' );
function cgd_media_attribution() { ?>
	<?php
	$prefix = '_cgd_';
	$title = get_post_meta( get_the_ID(), $prefix . 'attribution_title', true );
	$left_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'attribution_left_content', true ) );
	$right_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'attribution_right_content', true ) );
	?>
    <section id="attribution" class="content-section media-attribution">
		<div class="wrap">
			<h2 class="section-content-title"><?php echo $title; ?></h2>
			<div class="section-content-desc">
				<div class="one-half first">
					<?php echo do_shortcode($left_content); ?>
				</div>
				<div class="one-half">
					<?php echo do_shortcode($right_content); ?>
				</div>
			</div>
		</div>
	</section>
<?php }

add_action( 'genesis_after_header', 'cgd_media_assets' );
function cgd_media_assets() { ?>
	<?php
	$prefix = '_cgd_';
	$bio_title = get_post_meta( get_the_ID(), $prefix . 'asset_bio_title', true );
	$bio_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'asset_bio_content', true ) );
	$photo_title = get_post_meta( get_the_ID(), $prefix . 'asset_photo_title', true );
	$photo_content = wpautop( get_post_meta( get_the_ID(), $prefix . 'asset_photos_content', true ) );
	?>
    <section id="assets" class="content-section media-assets">
		<div class="wrap">
			<div class="one-half first">
				<h2 class="section-content-title"><?php echo $bio_title; ?></h2>
				<?php echo $bio_content; ?>
			</div>
			<div class="one-half">
				<h2 class="section-content-title"><?php echo $photo_title; ?></h2>
				<?php echo $photo_content; ?>
			</div>
		</div>
	</section>
<?php }

genesis();
