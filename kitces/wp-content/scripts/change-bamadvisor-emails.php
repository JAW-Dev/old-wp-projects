<?php
global $wpdb;
set_time_limit( 0 );
require( '../../wp/wp-load.php' );

function get_new_email_address( \WP_User $user ) {
	$email     = $user->user_email;
	$new_email = preg_replace( '/(.*)\@bamadvisor\.com$/', '$1@buckinghamgroup.com', $email );

	return $new_email;
}

function change_ac_email( \WP_User $user, $ac ) {
	$email     = $user->user_email;
	$new_email = get_new_email_address( $user );

	if ( ! $new_email ) {
		return false;
	}

	$ac_user        = $ac->api( "contact/view?email={$email}" );
	$ac_id          = $ac_user->id;
	$api_3_url      = 'https://kitces.api-us1.com/api/3/contacts/' . $ac_id;
	$http           = new \WP_Http();
	$request_params = array(
		'method'  => 'PUT',
		'headers' => array(
			'Api-Token' => 'befc79f7a64aad8af849ad6f8d417c99d00b879807377d39ab36d98f45e05738161a83c3',
		),
		'body'    => json_encode(
			array(
				'contact' => array(
					'email' => $new_email,
				),
			)
		),
	);
	$result         = $http->request( $api_3_url, $request_params );

	return 200 === ( $result['response']['code'] ?? false );
}

function change_wp_email( \WP_User $user ) {
	$user_id   = $user->ID;
	$new_email = get_new_email_address( $user );

	if ( ! $new_email ) {
		return false;
	}

	return wp_update_user(
		array(
			'ID'         => $user_id,
			'user_email' => $new_email,
			'user_login' => $new_email,
		)
	);
}

function change_email( \WP_User $user, $ac ) {
	if ( ! change_ac_email( $user, $ac ) ) {
		echo '<p>AC Issue: ' . (string) $user->ID . '</p>';
		return false;
	}

	if ( ! is_int( change_wp_email( $user ) ) ) {
		echo '<p>WP Issue: ' . (string) $user->ID . '</p>';
		return false;
	}
}

$query = array(
	'search_columns' => array( 'user_email' ),
	'search'         => '*@bamadvisor.com',
	'number'         => 10,
);

$users = get_users( $query );

echo '<p>Count:' . count( $users ) . '</p>';

$ac_ulr = 'https://kitces.api-us1.com';
$sc_key = 'befc79f7a64aad8af849ad6f8d417c99d00b879807377d39ab36d98f45e05738161a83c3';
$ac     = new \ActiveCampaign( $ac_ulr, $sc_key );

foreach ( $users as $user ) {
	change_email( $user, $ac );
}

die;
