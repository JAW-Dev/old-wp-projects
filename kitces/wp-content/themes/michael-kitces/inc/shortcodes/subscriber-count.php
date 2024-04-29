<?php

function objectiv_subscriber_count( $atts ) {

    $subscriber_count = get_field( 'current_subscriber_count', 'option' );

    if ( ! empty( $subscriber_count ) ) {
        return $subscriber_count;
    } else {
        return "";
    }

}

add_shortcode( 'sub-count', 'objectiv_subscriber_count' );
