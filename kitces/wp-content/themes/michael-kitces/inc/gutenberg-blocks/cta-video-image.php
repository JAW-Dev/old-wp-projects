<?php

add_action( 'acf/init', 'mk_register_video_cta_block' );
function mk_register_video_cta_block() {

	// check that the register function exists.
	if ( function_exists( 'acf_register_block_type' ) ) {

		acf_register_block_type(
			array(
				'name'            => 'mk-video-image-cta',
				'title'           => __( 'MK - Video/Image CTA' ),
				'description'     => __( 'A call to action section with a video or image to the side.' ),
				'render_callback' => 'mk_video_cta_block_render_callback',
				'keywords'        => array( 'kitces', 'video cta', 'image cta', 'call to action' ),
				'category'        => 'kitces-custom',
				'mode'            => 'preview',
				'align'           => 'full',
				'post_types'      => array( 'page' ),
				'supports'        => array(
					'align'           => false,
					'anchor'          => true,
					'customClassName' => true,
				),
			),
		);

	}
}

function mk_video_cta_block_render_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	// Block Meta Values
	$block_meta      = mk_block_meta( $block );
	$classes         = mk_key_value( $block_meta, 'classes' );
	$id              = mk_key_value( $block_meta, 'id' );
	$name            = mk_key_value( $block_meta, 'name' );
	$text_color      = mk_get_gb_field( 'text_color' );
	$ga_event_action = mk_key_value( $block_meta, 'ga_event_action' );

	// Block Data Values
	$content_side   = mk_get_gb_field( 'content_side' );
	$logo           = mk_get_gb_field( 'logo' );
	$intro_line     = mk_get_gb_field( 'intro_line' );
	$title          = mk_get_gb_field( 'title' );
	$blurb          = mk_get_gb_field( 'blurb' );
	$detail         = mk_get_gb_field( 'detail' );
	$blue_button    = mk_get_gb_field( 'blue_button' );
	$green_button   = mk_get_gb_field( 'green_button' );
	$footer_text    = mk_get_gb_field( 'footer_text' );
	$image          = mk_get_gb_field( 'image' );
	$vimeo_video_id = mk_get_gb_field( 'vimeo_video_id' );
	$logo_position  = mk_get_gb_field( 'logo_position' );
	$text_color     = 'tc-white'; // due to a request text now needs to be white

	// Page Setting
	$remove_title_section = mk_get_field( 'remove_title_section', get_the_ID(), true, true );

	?>
	<section class="mk-block <?php echo $classes; ?> <?php echo $text_color ?>" id="<?php echo $id; ?>" data-mk-block-event-action="<?php echo $ga_event_action; ?>">
		<div class="wrap">
			<?php if ( 'full-width' === $logo_position ) : ?>
				<?php if ( $logo ) : ?>
					<div class="logo mb3"><?php echo wp_get_attachment_image( $logo['id'], 'mk-xl', false, array( 'class' => 'block mlra' ) ); ?></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="<?php echo $name; ?>-inner <?php echo $content_side; ?>">
				<div class="content-side">
					<?php if ( 'full-width' !== $logo_position ) : ?>
						<?php if ( $logo ) : ?>
							<div class="logo"><?php echo wp_get_attachment_image( $logo['id'], array( '450' ), false, array( 'class' => 'block' ) ); ?></div>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $intro_line ) : ?>
						<div class="mthalf mbhalf italic"><?php echo wp_kses_post( $intro_line ); ?></div>
					<?php endif; ?>
					<?php if ( $title ) : ?>
						<?php if ( $remove_title_section ) : ?>
							<h1 class="fwb f36 <?php echo $text_color; ?>"><?php echo wp_kses_post( $title ); ?></h1>
						<?php else : ?>
							<h2 class="fwb f36 <?php echo $text_color; ?>"><?php echo wp_kses_post( $title ); ?></h2>
						<?php endif; ?>
					<?php endif; ?>
					<?php if ( $blurb ) : ?>
						<div class="fmt0 lmb0 small-spaced"><?php echo wp_kses_post( wpautop( $blurb ) ); ?></div>
					<?php endif; ?>
					<?php if ( $detail ) : ?>
						<div class="mt1 f22"><?php echo wp_kses_post( $detail ); ?></div>
					<?php endif; ?>
					<?php if ( $blue_button || $green_button ) : ?>
						<div class="button-wrap mt1">
							<?php if ( $blue_button ) : ?>
								<?php echo mk_link_html( $blue_button, 'button light-blue' ); ?>
							<?php endif; ?>
							<?php if ( $green_button ) : ?>
								<?php echo mk_link_html( $green_button, 'button' ); ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>
					<?php if ( $footer_text ) : ?>
						<div class="fmt0 lmb0 small-spaced mt1"><?php echo wp_kses_post( wpautop( $footer_text ) ); ?></div>
					<?php endif; ?>
				</div>
				<div class="other-side">
					<?php if ( $vimeo_video_id ) : ?>
						<div class="embedded-video-wrapper">
							<iframe src="https://player.vimeo.com/video/<?php echo $vimeo_video_id; ?>" frameborder="0" allow="fullscreen;" allowfullscreen></iframe>
						</div>
					<?php elseif ( $image ) : ?>
						<div class=""><?php echo wp_get_attachment_image( $image['id'], array( '800' ), false, array( 'class' => 'block' ) ); ?></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
	<?php
}
