<?php

/*
Template Name: Conference Landing Page
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'conference-landing-page';
	return $classes;

}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_loop', 'cgd_presentation_files' );
function cgd_presentation_files() { ?>
	<?php
	$prefix            = '_cgd_';
	$attachments       = get_post_meta( get_the_ID(), $prefix . 'conference_attachments_group', true );
	$attachments_title = get_post_meta( get_the_ID(), $prefix . 'conference_attachments_title', true );
	$attachments_desc  = get_post_meta( get_the_ID(), $prefix . 'conference_attachments_desc', true );
	$count             = 0;
	?>
	<?php if ( ! empty( $attachments ) ) : ?>
		<div class="conference-attachments">
			<?php if ( ! empty( $attachments_title ) ) : ?>
				<h2><?php echo $attachments_title; ?></h2>
			<?php endif; ?>
			<?php if ( ! empty( $attachments_desc ) ) : ?>
				<p><?php echo $attachments_desc; ?></p>
			<?php endif; ?>
			<?php if ( ! empty( $attachments ) ) : ?>
				<?php foreach ( $attachments as $attachment ) : ?>
					<?php
					if ( $count % 4 == 0 ) {
						$class = 'first';
					} else {
						$class = '';
					}
					?>
					<div class="conference-attachment one-fourth <?php echo $class; ?>">
						<a href="<?php echo $attachment['file']; ?>" target="_blank">
							<?php echo wp_get_attachment_image( $attachment['image_id'], 'medium' ); ?>
						</a>
					</div>
					<?php $count++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
}

add_action( 'genesis_loop', 'cgd_reading_materials' );
function cgd_reading_materials() {

	$reading_title_new      = mk_get_field( 'reading_materials_title', get_the_ID() );
	$reading_desc_new       = mk_get_field( 'reading_materials_description', get_the_ID() );
	$reading_link_lists_new = mk_get_field( 'reading_materials_link_lists', get_the_ID(), true, true );

	if ( is_array( $reading_link_lists_new ) && ! empty( $reading_link_lists_new ) ) {
		?>
			<div class="conference-reading-materials norm-list">
				<?php if ( ! empty( $reading_title_new ) ) : ?>
					<h2><?php echo $reading_title_new; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $reading_desc_new ) ) : ?>
					<p><?php echo $reading_desc_new; ?></p>
				<?php endif; ?>
				<?php foreach ( $reading_link_lists_new as $list ) : ?>
					<?php
						$name         = mk_get_field( 'link_list_display_title', $list );
						$links        = mk_get_field( 'link_list_links', $list, true, true );
						$display_list = is_array( $links ) && ! empty( $links );

					if ( empty( $name ) ) {
						$name = get_the_title( $list );
					}
					?>
					<?php if ( $display_list ) : ?>
						<div class="reading-list-wrap">
							<?php if ( ! empty( $name ) ) : ?>
								<p class="post-list-title fwb f20 mt1 mbhalf"><?php echo $name; ?></p>
							<?php endif; ?>
							<ul style="margin-top: 0px;">
								<?php foreach ( $links as $link ) : ?>
									<?php $actual_link = mk_key_value( $link, 'link' ); ?>
									<li><?php echo mk_link_html( $actual_link ); ?></li>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		<?php
	} else {
		$prefix          = '_cgd_';
		$materials       = get_post_meta( get_the_ID(), $prefix . 'conference_reading_materials_group', true );
		$materials_title = get_post_meta( get_the_ID(), $prefix . 'conference_reading_materials_title', true );
		$materials_desc  = get_post_meta( get_the_ID(), $prefix . 'conference_reading_materials_desc', true );

		if ( ! empty( $materials ) ) :
			?>
			<div class="conference-reading-materials norm-list">
				<?php if ( ! empty( $materials_title ) ) : ?>
					<h2><?php echo $materials_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $materials_desc ) ) : ?>
					<p><?php echo $materials_desc; ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $materials ) ) : ?>
					<div class="reading-list-wrap">
						<ul style="margin-top: 0px;">
							<?php foreach ( $materials as $post ) : ?>
								<li><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>
			<?php
		endif;
	}
}

add_action( 'genesis_loop', 'cgd_thrive_conference_optin' );
function cgd_thrive_conference_optin() {
	$prefix = '_cgd_';

	$oim_code        = get_post_meta( get_the_ID(), $prefix . 'conference_oim_optin_shortcode', true );
	$global_oim_code = get_field( 'global_conference_template_cta_shortcode', 'option' );

	if ( empty( $oim_code ) && ! empty( $global_oim_code ) ) {
		$oim_code = $global_oim_code;
	}

	?>
	<?php if ( ! empty( $oim_code ) ) : ?>
		<?php echo do_shortcode( $oim_code ); ?>
	<?php endif; ?>
	<?php
}

genesis();
