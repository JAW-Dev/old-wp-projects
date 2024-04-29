<?php

if ( ! function_exists( 'mk_key_value' ) ) {
	function mk_key_value( $incoming_array = null, $key = null ) {
		if ( is_array( $incoming_array ) && ! empty( $key ) ) {
			if ( array_key_exists( $key, $incoming_array ) && ! empty( $incoming_array[ $key ] ) ) {
				return $incoming_array[ $key ];
			}
		}
		return false;
	}
}

function mk_get_field( $selector = null, $post_id = null, $format_value = true, $use_acf = false ) {
	$result = null;

	if ( $use_acf ) {
		// Allow for just using ACF
		$result = get_field( $selector, $post_id, $format_value );
	} else {

		// A helpful little function that doesn't do too much but returns an id we can use
		$post_id = acf_get_valid_post_id( $post_id );

		// Grab options setting if that is what is set, otherwise get post meta
		if ( $post_id == 'options' ) {
			$result = get_option( 'options_' . $selector );
		} else {
			$result = get_post_meta( $post_id, $selector, true );
		}

		// Fall back to ACF field selector if we don't have anything
		if ( empty( $result ) ) {
			$result = get_field( $selector, $post_id, $format_value );
		}
	}

	if ( empty( $result ) ) {
		$result = false;
	}

	return $result;
}
