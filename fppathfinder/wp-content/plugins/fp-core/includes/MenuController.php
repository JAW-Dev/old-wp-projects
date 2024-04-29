<?php

namespace FP_Core;

/**
 * Menu Controller
 */
class MenuController {
	public function __construct() {
		add_filter( 'wp_nav_menu_objects', array( $this, 'maybe_remove_become_a_member_menu_item' ) );
		add_filter( 'nav_menu_item_args', array( $this, 'turn_become_a_member_link_into_button' ), 10, 3 );
	}

	public function maybe_remove_become_a_member_menu_item( $menu_items ) {
		$user_id = get_current_user_id();

		if ( ! $user_id ) {
			return $menu_items;
		}

		$member = new Member( $user_id );

		if ( ! $member->is_active() ) {
			return $menu_items;
		}

		foreach ( $menu_items as $key => $menu_item ) {
			if ( 415 !== $menu_item->ID ) {
				continue;
			}

			global $rcp_options;

			if ( $member->can_upgrade() && get_the_ID() !== (int) ( $rcp_options['account_page'] ?? 0 ) || $member->is_active_at_level( 6 ) ) {
				$menu_items[ $key ]->title = 'Upgrade';
			} else {
				unset( $menu_items[ $key ] );
			}
		}

		return $menu_items;
	}

	public function turn_become_a_member_link_into_button( $args, $item, $depth ) {
		$is_become_member = 415 === $item->ID;
		$args->before     = $is_become_member ? '<span class="button red-button" style="margin-left: 1rem;">' : '';
		$args->after      = $is_become_member ? '</span>' : '';

		return $args;
	}
}
