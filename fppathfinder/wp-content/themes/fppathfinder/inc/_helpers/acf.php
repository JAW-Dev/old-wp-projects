<?php

/**
 * Add Member Section Options
 */
if ( function_exists( 'acf_add_options_page' ) ) {

	acf_add_options_page(
		array(
			'page_title' => 'Theme Settings',
			'menu_title' => 'Theme',
			'menu_slug'  => 'theme-general-settings',
			'icon_url'   => 'dashicons-art',
			'capability' => 'edit_posts',
			'position'   => 59.5,
			'redirect'   => false,
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Member Section Settings',
			'menu_title'  => 'Member Section',
			'parent_slug' => 'theme-general-settings',
			'menu_slug'   => 'member-section-general-settings',
			'icon_url'    => 'dashicons-admin-generic',
			'capability'  => 'edit_posts',
			'position'    => 59.5,
			'redirect'    => false,
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Upsell Modals',
			'menu_title'  => 'Upsell Modals',
			'parent_slug' => 'theme-general-settings',
			'menu_slug'   => 'upsell-modals-settings',
			'capability'  => 'edit_posts',
			'position'    => 59.5,
			'redirect'    => false,
			'post_id'     => 'upsell_modals',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'NPS Survey Settings',
			'menu_title'  => 'NPS Survey',
			'parent_slug' => 'theme-general-settings',
			'menu_slug'   => 'nps-survey-settings',
			'capability'  => 'edit_posts',
			'position'    => 59.5,
			'redirect'    => false,
			'post_id'     => 'nps_settings',
		)
	);

	acf_add_options_sub_page(
		array(
			'page_title'  => 'Footer CTA Settings',
			'menu_title'  => 'Footer CTA',
			'parent_slug' => 'theme-general-settings',
			'menu_slug'   => 'footer-cta-settings',
			'icon_url'    => 'dashicons-filter',
			'capability'  => 'edit_posts',
			'position'    => 59.5,
			'redirect'    => false,
		)
	);

}
