<?php

function one_half_func( $atts, $content = null ) {
    return '<div class="one-half first">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'one_half', 'one_half_func' );

function one_half_last_func( $atts, $content = null ) {
    return '<div class="one-half">' . do_shortcode( $content ) . '</div><div style="clear:both;"></div>';
}

add_shortcode( 'one_half_last', 'one_half_last_func' );

function content_block_func( $atts, $content = null ) {
    $a = shortcode_atts( array(
        'height_attr' => '',
	), $atts );
    return '<div class="content-block is-' . $a['height_attr'] . '">' . do_shortcode( $content ) . '</div>';
}

add_shortcode( 'content_block', 'content_block_func' );
