<?php

set_time_limit( 0 );
require '../../wp/wp-load.php';
require '../vendor/autoload.php';

function add_new_ac_tag( $tag_name ) {

	// Bail if no tag name set.
	if ( empty( $tag_name ) ) {
		return;
	}

	$ac_api_url = 'https://kitces.api-us1.com';
	$ac_api_key = 'befc79f7a64aad8af849ad6f8d417c99d00b879807377d39ab36d98f45e05738161a83c3';
	$ac_api     = new ActiveCampaign( $ac_api_url, $ac_api_key );

	// Get the list of existing tags.
	$existing_tags = json_decode( $ac_api->api( 'tags/list' ) );

	// Build an array of the existing tag names.
	if ( ! empty( $existing_tags ) ) {
		$existing_tag_names = array();

		foreach ( $existing_tags as $tag ) {
			if ( ! in_array( $tag->name, $existing_tag_names, true ) ) {
				$existing_tag_names[] = $tag->name;
			}
		}

		// If the renewed tag name isn't in the existing tags array
		// create a new tag.
		if ( ! in_array( $tag_name, $existing_tag_names, true ) ) {
			$new_tag = array(
				'tag' => array(
					'tag'     => $tag_name,
					'tagType' => 'contact',
				),
			);

			// AC API doesn't have a method for creating tags
			// Use wp_remote_post to do a direct API post.
			$response = wp_remote_post(
				$ac_api_url . '/api/3/tags?api_key=' . $ac_api_key,
				array( 'body' => wp_json_encode( $new_tag ) )
			);
		}
	}
}

$test = 'Test-Tag-2020';

add_new_ac_tag( $test );




