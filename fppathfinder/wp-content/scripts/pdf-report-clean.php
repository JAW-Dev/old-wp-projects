<?php

require '../../wp-load.php';

$post_id = isset( $_GET['post_id'] ) ? sanitize_text_field( wp_unslash( $_GET['post_id'] ) ) : '';
$user_id  = isset( $_GET['user_id'] ) ? sanitize_text_field( wp_unslash( $_GET['user_id'] ) ) : '';

if ( empty( $post_id ) || empty( $user_id ) ) {
	return;
}

$downloads = get_post_meta( $post_id, 'times_downloaded', true );

foreach ( $downloads as $key => $value ) {
	if ( (int) $value['user_id'] === (int) $user_id ) {
		unset( $downloads[ $key ] );
	}
}

update_post_meta( (int) $post_id, 'times_downloaded', $downloads );
