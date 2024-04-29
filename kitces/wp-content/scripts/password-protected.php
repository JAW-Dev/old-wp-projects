<?php
require '../../wp/wp-load.php';

$plugin_file = 'password-protected/password-protected.php';
$active_plugins = get_option( 'active_plugins' );

// If you get locked out this will disable the plugin.

// foreach ( $active_plugins as $key => $value ) {
// 	if ( $value === $plugin_file ) {
// 		unset( $active_plugins[ $key ] );
// 	}
// }
// update_option( 'active_plugins', $active_plugins );

if ( isset( $active_plugins[ $plugin_file ] ) ) {
	options();
	return;
}

require_once ABSPATH . 'wp-admin/includes/plugin.php';
$result = activate_plugin( $plugin_file );

if ( is_wp_error( $result ) ) {
	error_log( ': ' . print_r( $result->get_error_message(), true ) ); // phpcs:ignore
}

options();


function options() {

	$options = array(
		'password_protected_status'               => 1,
		'password_protected_feeds'                => 0,
		'password_protected_rest'                 => 0,
		'password_protected_administrators'       => 1,
		'password_protected_users'                => 0,
		'password_protected_allowed_ip_addresses' => '',
		'password_protected_remember_me_lifetime' => 14,
		'password_protected_password'             => md5( 'guest' ),
	);

	update_option( 'blog_public', '0' );

	foreach ( $options as $key => $value ) {
		if ( null !== get_option( $key ) ) {
			update_option( $key, $value );
		}
	}
}

