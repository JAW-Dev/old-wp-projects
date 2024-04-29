<?php

function obj_key_value( $incoming_array = null, $key = null ) {
	if ( is_array( $incoming_array ) && ! empty( $key ) ) {
		if ( array_key_exists( $key, $incoming_array ) && ! empty( $incoming_array[ $key ] ) ) {
			return $incoming_array[ $key ];
		}
	}

	return false;
}
