<?php

function obj_user_first_name_shortcode() {
	$first_name = obj_get_users_first_name();

	if ( empty( $first_name ) ) {
		$first_name = 'Friend';
	}

	return $first_name;
}
add_shortcode( 'user_first_name', 'obj_user_first_name_shortcode' );
