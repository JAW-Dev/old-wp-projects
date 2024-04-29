<?php

function get_current_item_levels_links( $pid = null ) {
	if ( ! empty( $pid ) && function_exists( 'rcp_get_content_subscription_levels' ) ) {
		$sub_levels  = rcp_get_content_subscription_levels( $pid );
		$mem_page    = get_the_permalink( get_field( 'become_member_page', 'option' ) );
		$level_links = array();

		if ( ! empty( $sub_levels ) ) {
			foreach ( $sub_levels as $sl ) {
				$levels     = new RCP_Levels();
				$level      = $levels->get_level( $sl );
				$level_name = $level->name;

				if ( ! empty( $level_name ) && ! empty( $mem_page ) ) {
					$the_level_link = '<a href=' . $mem_page . '>' . $level_name . '</a>';
					array_push( $level_links, $the_level_link );
				}
			}

			return $level_links;
		} else {
			return null;
		}
	}

	return null;
}

function get_post_rcp_levels( $pid = null ) {
	if ( ! empty( $pid ) ) {
		$sub_levels = rcp_get_content_subscription_levels( $pid );
		if ( ! empty( $sub_levels ) ) {
			return $sub_levels;
		} else {
			return null;
		}
	}
	return null;
}

function get_current_download_cat_links( $pid = null ) {
	if ( ! empty( $pid ) ) {
		$d_cats    = get_the_terms( $pid, 'download-cat' );
		$cat_links = array();

		if ( ! empty( $d_cats ) ) {
			foreach ( $d_cats as $d_cat ) {
				$cat_name = $d_cat->name;
				$cat_perm = get_term_link( $d_cat->term_id );

				if ( ! empty( $cat_name ) && ! empty( $cat_perm ) ) {
					$the_cat_link = '<a href=' . $cat_perm . '>' . $cat_name . '</a>';
					array_push( $cat_links, $the_cat_link );
				}
			}

			return $cat_links;
		} else {
			return null;
		}
	}

	return null;
}

function get_all_rcp_levels() {
	if ( function_exists( 'rcp_get_content_subscription_levels' ) ) {
		$active_levels = rcp_get_memberships(
			array(
				'status' => 'active',
			)
		);
		if ( ! empty( $active_levels ) ) {
			return $active_levels;

		} else {
			return null;
		}
	}

	return null;
}

function obj_get_users_first_name() {
	$first_name = null;

	if ( is_user_logged_in() ) {
		$user = wp_get_current_user();

		if ( $user ) {
			$first_name = $user->data->display_name;
		}
	}

	return $first_name;

}

function obj_get_users_full_name() {
	$full_name = null;

	if ( is_user_logged_in() ) {
		$user_id    = get_current_user_id();
		$first_name = get_user_meta( $user_id, 'first_name', true );
		$last_name  = get_user_meta( $user_id, 'last_name', true );

		if ( ! empty( $first_name ) && ! empty( $last_name ) ) {
			$full_name = $first_name . ' ' . $last_name;
		}
	}

	return $full_name;
}

function obj_get_users_email() {
	$return_email = null;

	if ( is_user_logged_in() ) {
		$user  = wp_get_current_user();
		$email = $user->user_email;

		if ( $email ) {
			$return_email = $email;
		}
	}

	return $return_email;
}

function obj_get_users_subscription_level_name() {
	$sub_name = null;

	if ( is_user_logged_in() ) {
		$level_names = rcp_get_customer_membership_level_names();

		if ( ! empty( $level_names ) && is_array( $level_names ) ) {
			$sub_name = $level_names[0];
		}
	}

	return $sub_name;
}
