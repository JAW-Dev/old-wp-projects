<?php

function nerd_notes_function( $atts, $content = null ) {

	$image_url = get_stylesheet_directory_uri() . '/images/kitces-8bit.png';

	if ( ! empty( $atts ) && is_array( $atts ) && array_key_exists( 'author_id', $atts ) ) {
		$author_id  = mk_key_value( $atts, 'author_id' );
		$author_img = mk_get_field( 'nerd_note_image', 'user_' . $author_id, true, true );

		if ( ! empty( $author_img ) && is_array( $author_img ) ) {
			$img_sizes = mk_key_value( $author_img, 'sizes' );
			$image_url = mk_key_value( $img_sizes, 'thumbnail' );
		}
	}

	if ( ! empty( $content ) ) {
		ob_start();

		?>
		<div class="nerd-note">
			<div class="nerd-note-header">
				<img src="<?php echo $image_url; ?>" alt="Nerd Note Author Avatar">
				<h4>Nerd Note:</h4>
			</div>
			<div class="nerd-note-content">
				<?php echo wpautop( $content ); ?>
			</div>
		</div>
		<?php
		return ob_get_clean();
	} else {
		return null;
	}

}
add_shortcode( 'nerdnote', 'nerd_notes_function' );
