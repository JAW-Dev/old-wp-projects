<?php

function oim_pl_shortcode( $atts ) {

	$return_html     = null;
	$podcast_cta     = get_field( 'podcast_optin_shortcode_content', 'option' );
	$new_podcast_cta = get_field( 'podcast_optin_new_podcast_cta', 'option' );

	if ( $new_podcast_cta ) {
		$new_blurb      = get_field( 'podcast_email_cta_blurb', 'option' );
		$do_sub_buttons = get_field( 'display_subscribe_buttons_in_podcast_cta', 'option' );
		$new_form       = podcast_email_form_shortcode();

		if ( ! empty( $new_blurb ) && ! empty( $new_form ) ) {
			ob_start();
			?>
				<div class="podcast-post-email-cta subscribe-section-inner bg-light-gray">
					<div class="subscribe-blurb"><?php echo $new_blurb; ?></div>
					<?php if ( $do_sub_buttons ) : ?>
						<?php echo objectiv_fa_get_sub_buttons_html( 'mb1' ); ?>
					<?php endif; ?>
					<div class="subscribe-email-form-wrap"><?php echo do_shortcode( $new_form ); ?></div>
				</div>
			<?php
			$return_html = ob_get_contents();
			ob_end_clean();
		}
	} else {
		$return_html = $podcast_cta;
	}

	return $return_html;
}

add_shortcode( 'oim-pl', 'oim_pl_shortcode' );


function podcast_email_form_shortcode( $atts = null ) {

	$podcast_email_form = get_field( 'podcast_email_form_shortcode', 'option' );

	if ( ! empty( $podcast_email_form ) ) {
		return $podcast_email_form;
	} else {
		return '';
	}

}

add_shortcode( 'pcast-email-sub', 'podcast_email_form_shortcode' );
