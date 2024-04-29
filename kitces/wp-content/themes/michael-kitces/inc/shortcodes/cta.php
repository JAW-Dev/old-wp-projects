<?php

function cta_func( $atts ) {
    $a = shortcode_atts( array(
		'id' => '1',
	), $atts );
    $page_id = $a['id'];
    $cta_content = get_post_meta( get_the_ID(), '_cgd_start_cta_content', true );
    ob_start();
    ?>
    <div class="start-cta">
        <?php echo do_shortcode( $cta_content ); ?>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode( 'cta', 'cta_func' );
