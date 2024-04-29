<?php
/**
 * Include metabox on front page
 *
 * @author Ed Townend
 * @link https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-show_on-filters
 *
 * @param bool  $display
 * @param array $meta_box
 * @return bool display metabox
 */
function ed_metabox_include_front_page( $display, $meta_box ) {
	if ( ! isset( $meta_box['show_on']['key'] ) ) {
		return $display;
	}

	if ( 'front-page' !== $meta_box['show_on']['key'] ) {
		return $display;
	}

	$post_id = 0;

	// If we're showing it based on ID, get the current ID
	if ( isset( $_GET['post'] ) ) {
		$post_id = $_GET['post'];
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = $_POST['post_ID'];
	}

	if ( ! $post_id ) {
		return false;
	}

	// Get ID of page set as front page, 0 if there isn't one
	$front_page = get_option( 'page_on_front' );

	// there is a front page set and we're on it!
	return $post_id == $front_page;
}
add_filter( 'cmb2_show_on', 'ed_metabox_include_front_page', 10, 2 );

function cgd_sanitize_text_callback( $value, $field_args, $field ) {

	/*
	 * Do your custom sanitization.
	 * strip_tags can allow whitelisted tags
	 * http://php.net/manual/en/function.strip-tags.php
	 */
	$value = strip_tags( $value, '<p><a><br><br/><sub><sup>' );

	return $value;
}

add_action( 'cmb2_admin_init', '_cgd_register_demo_metabox' );

function _cgd_register_demo_metabox() {

	$prefix = '_cgd_';

	$hero = new_cmb2_box(
		array(
			'id'           => $prefix . 'hero_settings',
			'title'        => 'Hero Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'front-page',
				'value' => '',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$hero->add_field(
		array(
			'id'   => $prefix . 'hero_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$hero->add_field(
		array(
			'id'   => $prefix . 'hero_subtitle',
			'name' => 'Sub-Title',
			'type' => 'textarea',
		)
	);

	$hero->add_field(
		array(
			'id'   => $prefix . 'hero_optin',
			'name' => 'Optin Message',
			'type' => 'textarea',
		)
	);

	$hero->add_field(
		array(
			'id'   => $prefix . 'hero_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$hero->add_field(
		array(
			'id'   => $prefix . 'hero_thrive_id',
			'name' => 'Thrive Lightbox ID',
			'type' => 'text',
		)
	);

	$hero->add_field(
		array(
			'id'   => $prefix . 'hero_oim_id',
			'name' => 'OIM Lightbox ID',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_icon_nav_settings' );
function cgd_register_icon_nav_settings() {

	$prefix = '_cgd_';

	$nav_icons = new_cmb2_box(
		array(
			'id'           => $prefix . 'nav_icon_settings',
			'title'        => 'Navigation Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$icons = $nav_icons->add_field(
		array(
			'id'          => $prefix . 'icon_group',
			'type'        => 'group',
			'description' => 'Add the icons for the navigation area.',
			'options'     => array(
				'group_title'   => 'Icon {#}',
				'add_button'    => 'Add Icon',
				'remove_button' => 'Remove Icon',
				'sortable'      => true,
			),
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'icon',
			'name' => 'Icon',
			'desc' => 'Enter the Font Awesome class of the icon that you want to display. I.E. "fa-user"',
			'type' => 'text',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_icon_nav_settings_2' );
function cgd_register_icon_nav_settings_2() {

	$prefix = '_cgd_';

	$nav_icons = new_cmb2_box(
		array(
			'id'           => $prefix . 'nav_icon_settings_2',
			'title'        => 'Navigation Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here-2.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$icons = $nav_icons->add_field(
		array(
			'id'          => $prefix . 'icon_group_2',
			'type'        => 'group',
			'description' => 'Add the icons for the navigation area.',
			'options'     => array(
				'group_title'   => 'Icon {#}',
				'add_button'    => 'Add Icon',
				'remove_button' => 'Remove Icon',
				'sortable'      => true,
			),
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'icon',
			'name' => 'Icon',
			'desc' => 'Enter the Font Awesome class of the icon that you want to display. I.E. "fa-user"',
			'type' => 'text',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_start_cta_fields' );
function cgd_register_start_cta_fields() {
	$prefix = '_cgd_';

	$cta = new_cmb2_box(
		array(
			'id'           => $prefix . 'start_cta_settings',
			'title'        => 'CTA Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here-2.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$cta->add_field(
		array(
			'id'   => $prefix . 'start_cta_content',
			'name' => 'Name',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_start_tabs' );
function cgd_register_start_tabs() {
	$prefix = '_cgd_';

	$best_categories = new_cmb2_box(
		array(
			'id'           => $prefix . 'pop_tabs_cats',
			'title'        => 'Category Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here-2.php',
			),
		)
	);

	$categories = $best_categories->add_field(
		array(
			'id'      => $prefix . 'pop_cat_group',
			'name'    => 'Categories',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Category {#}',
				'add_button'    => 'Add Another Category',
				'remove_button' => 'Remove Category',
				'sortable'      => true,
			),
		)
	);

	$best_categories->add_group_field(
		$categories,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$best_categories->add_group_field(
		$categories,
		array(
			'id'   => 'id',
			'name' => 'Category ID',
			'type' => 'text',
		)
	);

	$best_posts = new_cmb2_box(
		array(
			'id'           => $prefix . 'pop_tabs_posts',
			'title'        => 'Post Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here-2.php',
			),
		)
	);

	$posts = $best_posts->add_field(
		array(
			'id'      => $prefix . 'pop_posts_group',
			'name'    => 'Posts',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Post {#}',
				'add_button'    => 'Add Another Post',
				'remove_button' => 'Remove Post',
				'sortable'      => true,
			),
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'guest',
			'name' => 'Is this a guest post?',
			'type' => 'checkbox',
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'id',
			'name' => 'Category ID',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_start_welcome_settings' );

function cgd_register_start_welcome_settings() {

	$prefix = '_cgd_';

	$welcome = new_cmb2_box(
		array(
			'id'           => $prefix . 'welcome_settings',
			'title'        => 'Welcome Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$welcome->add_field(
		array(
			'id'   => $prefix . 'welcome_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$welcome->add_field(
		array(
			'id'   => $prefix . 'welcome_top_content',
			'name' => 'Top Content',
			'type' => 'wysiwyg',
		)
	);

	$welcome->add_field(
		array(
			'id'   => $prefix . 'welcome_middle_content',
			'name' => 'Middle Content',
			'type' => 'wysiwyg',
		)
	);

	$top_buttons = $welcome->add_field(
		array(
			'id'          => $prefix . 'welcome_top_buttons_group',
			'type'        => 'group',
			'description' => 'Add the buttons for the top content section of the Welcome area.',
			'options'     => array(
				'group_title'   => 'Button {#}',
				'add_button'    => 'Add Button',
				'remove_button' => 'Remove Button',
				'sortable'      => true,
			),
		)
	);

	$welcome->add_group_field(
		$top_buttons,
		array(
			'name' => 'Text',
			'id'   => 'text',
			'type' => 'text',
		)
	);

	$welcome->add_group_field(
		$top_buttons,
		array(
			'name' => 'Link',
			'id'   => 'link',
			'type' => 'text_url',
		)
	);

	$welcome->add_group_field(
		$top_buttons,
		array(
			'name' => 'Button Icon',
			'id'   => 'icon',
			'type' => 'text',
		)
	);

	$welcome->add_field(
		array(
			'id'   => $prefix . 'welcome_bottom_content',
			'name' => 'Bottom Content',
			'type' => 'wysiwyg',
		)
	);

	$bottom_buttons = $welcome->add_field(
		array(
			'id'          => $prefix . 'welcome_bottom_buttons_group',
			'type'        => 'group',
			'description' => 'Add the buttons for the bottom content section of the Welcome area.',
			'options'     => array(
				'group_title'   => 'Button {#}',
				'add_button'    => 'Add Button',
				'remove_button' => 'Remove Button',
				'sortable'      => true,
			),
		)
	);

	$welcome->add_group_field(
		$bottom_buttons,
		array(
			'name' => 'Optin Monster Button?',
			'id'   => 'optin_button',
			'type' => 'checkbox',
		)
	);

	$welcome->add_group_field(
		$bottom_buttons,
		array(
			'name' => 'Text',
			'id'   => 'text',
			'type' => 'text',
		)
	);

	$welcome->add_group_field(
		$bottom_buttons,
		array(
			'name' => 'Link',
			'id'   => 'link',
			'type' => 'text_url',
		)
	);

	$welcome->add_group_field(
		$bottom_buttons,
		array(
			'name' => 'Button Icon',
			'id'   => 'icon',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_start_members_cta' );
function cgd_register_start_members_cta() {
	$prefix = '_cgd_';

	$members = new_cmb2_box(
		array(
			'id'           => $prefix . 'member_cta_settings',
			'title'        => 'Member Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$members->add_field(
		array(
			'id'   => $prefix . 'member_cta_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$members->add_field(
		array(
			'id'   => $prefix . 'member_cta_desc',
			'name' => 'description',
			'type' => 'textarea',
		)
	);

	$members->add_field(
		array(
			'id'   => $prefix . 'member_cta_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$members->add_field(
		array(
			'id'   => $prefix . 'member_cta_button_url',
			'name' => 'Button Link',
			'type' => 'text_url',
		)
	);

	$members->add_field(
		array(
			'id'   => $prefix . 'member_cta_button_icon',
			'name' => 'Button Icon',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_solutions_metaboxes' );
function cgd_register_solutions_metaboxes() {
	$prefix = '_cgd_';

	$solutions = new_cmb2_box(
		array(
			'id'           => $prefix . 'solutions_settings',
			'title'        => 'Solution Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$solutions->add_field(
		array(
			'id'   => $prefix . 'solution_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$solutions->add_field(
		array(
			'id'   => $prefix . 'solution_desc',
			'name' => 'description',
			'type' => 'textarea',
		)
	);

	$solution = $solutions->add_field(
		array(
			'id'          => $prefix . 'solution_group',
			'type'        => 'group',
			'description' => 'Add the solution group.',
			'options'     => array(
				'group_title'   => 'Entry {#}',
				'add_button'    => 'Add Entry',
				'remove_button' => 'Remove Entry',
				'sortable'      => true,
			),
		)
	);

	$solutions->add_group_field(
		$solution,
		array(
			'name' => 'Title',
			'id'   => 'title',
			'type' => 'text',
		)
	);

	$solutions->add_group_field(
		$solution,
		array(
			'name' => 'Description',
			'id'   => 'desc',
			'type' => 'textarea',
		)
	);

	$solutions->add_group_field(
		$solution,
		array(
			'name' => 'Button Text',
			'id'   => 'button_text',
			'type' => 'text',
		)
	);

	$solutions->add_group_field(
		$solution,
		array(
			'name' => 'Button Text',
			'id'   => 'button_text',
			'type' => 'text',
		)
	);

	$solutions->add_group_field(
		$solution,
		array(
			'name' => 'Button Link',
			'id'   => 'button_link',
			'type' => 'text_url',
		)
	);

	$solutions->add_group_field(
		$solution,
		array(
			'name' => 'Button Icon',
			'id'   => 'button_icon',
			'type' => 'text',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_work_section_metaboxes' );
function cgd_register_work_section_metaboxes() {
	$prefix = '_cgd_';

	$work = new_cmb2_box(
		array(
			'id'           => $prefix . 'work_settings',
			'title'        => 'Work With Michael Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$work->add_field(
		array(
			'id'   => $prefix . 'work_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$work->add_field(
		array(
			'id'   => $prefix . 'work_desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$work_ctas = $work->add_field(
		array(
			'id'          => $prefix . 'work_ctas',
			'type'        => 'group',
			'description' => 'Add the solution group.',
			'options'     => array(
				'group_title'   => 'Entry {#}',
				'add_button'    => 'Add Entry',
				'remove_button' => 'Remove Entry',
				'sortable'      => true,
			),
		)
	);

	$work->add_group_field(
		$work_ctas,
		array(
			'name' => 'Title',
			'id'   => 'title',
			'type' => 'text',
		)
	);

	$work->add_group_field(
		$work_ctas,
		array(
			'name' => 'Description',
			'id'   => 'desc',
			'type' => 'textarea',
		)
	);

	$work->add_group_field(
		$work_ctas,
		array(
			'name' => 'Button Text',
			'id'   => 'button_text',
			'type' => 'text',
		)
	);

	$work->add_group_field(
		$work_ctas,
		array(
			'name' => 'Button Link',
			'id'   => 'button_link',
			'type' => 'text_url',
		)
	);

	$work->add_group_field(
		$work_ctas,
		array(
			'name' => 'Button Icon',
			'id'   => 'button_icon',
			'type' => 'text',
		)
	);

	$work->add_field(
		array(
			'id'   => $prefix . 'work_bottom_content',
			'name' => 'Bottom Content',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_new_offerings_section' );
function cgd_register_new_offerings_section() {
	$prefix = '_cgd_';

	$offerings = new_cmb2_box(
		array(
			'id'           => $prefix . 'offerings_settings',
			'title'        => 'New Offerings Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$offerings->add_field(
		array(
			'id'   => $prefix . 'offerings_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$offerings->add_field(
		array(
			'id'   => $prefix . 'offerings_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

	$offerings->add_field(
		array(
			'id'   => $prefix . 'offerings_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$offerings->add_field(
		array(
			'id'   => $prefix . 'offerings_button_url',
			'name' => 'Button Link',
			'type' => 'text_url',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_start_contact_metaboxes' );
function cgd_register_start_contact_metaboxes() {
	$prefix = '_cgd_';

	$contact = new_cmb2_box(
		array(
			'id'           => $prefix . 'contact_settings',
			'title'        => 'Contact Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-start-here.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$contact->add_field(
		array(
			'id'   => $prefix . 'contact_content',
			'name' => 'Content',
			'type' => 'textarea',
		)
	);

	$contact->add_field(
		array(
			'id'   => $prefix . 'contact_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$contact->add_field(
		array(
			'id'   => $prefix . 'contact_button_link',
			'name' => 'Button Link',
			'type' => 'text_url',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_members_description_section' );
function cgd_register_members_description_section() {
	$prefix = '_cgd_';

	$desc = new_cmb2_box(
		array(
			'id'           => $prefix . 'members_description',
			'title'        => 'Description Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$desc->add_field(
		array(
			'id'              => $prefix . 'members_desc_title',
			'name'            => 'Title',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$desc->add_field(
		array(
			'id'   => $prefix . 'members_desc_content',
			'name' => 'Content',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_members_features_metaboxes' );
function cgd_register_members_features_metaboxes() {
	$prefix = '_cgd_';

	$features = new_cmb2_box(
		array(
			'id'           => $prefix . 'members_features',
			'title'        => 'Features Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$features->add_field(
		array(
			'id'              => $prefix . 'features_title',
			'name'            => 'Title',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$features->add_field(
		array(
			'id'   => $prefix . 'features_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

	$features_group = $features->add_field(
		array(
			'id'          => $prefix . 'features_group',
			'type'        => 'group',
			'description' => 'Add the features group.',
			'options'     => array(
				'group_title'   => 'Feature {#}',
				'add_button'    => 'Add Feature',
				'remove_button' => 'Remove Feature',
				'sortable'      => true,
			),
		)
	);

	$features->add_group_field(
		$features_group,
		array(
			'id'   => 'icon',
			'name' => 'Icon',
			'type' => 'text',
		)
	);

	$features->add_group_field(
		$features_group,
		array(
			'id'              => 'title',
			'name'            => 'Name',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$features->add_group_field(
		$features_group,
		array(
			'id'              => 'desc',
			'name'            => 'Description',
			'type'            => 'textarea',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$features->add_group_field(
		$features_group,
		array(
			'id'   => 'button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$features->add_group_field(
		$features_group,
		array(
			'id'   => 'button_url',
			'name' => 'Button URL',
			'type' => 'text_url',
		)
	);

	$features->add_field(
		array(
			'id'   => $prefix . 'features_bottom_content',
			'name' => 'Bottom Content',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_member_pricing_metagoxes' );
function cgd_register_member_pricing_metagoxes() {
	$prefix = '_cgd_';

	$features = new_cmb2_box(
		array(
			'id'           => $prefix . 'members_pricing',
			'title'        => 'Pricing Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$features->add_field(
		array(
			'id'              => $prefix . 'members_pricing_desc',
			'name'            => 'Description',
			'type'            => 'textarea',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_pricing_table_metaboxes' );
function cgd_register_pricing_table_metaboxes() {
	$prefix = '_cgd_';

	$pricing = new_cmb2_box(
		array(
			'id'           => $prefix . 'pricing_table_product_settings',
			'title'        => 'Pricing Table Product Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_regular_title',
			'name'            => 'Regular Product Title',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_regular_price',
			'name'            => 'Regular Product Price',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_regular_duration',
			'name'            => 'Regular Product Duration',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_regular_subscription_link',
			'name'            => 'Regular Subscription Link',
			'type'            => 'text_url',
			'sanitization_cb' => false,
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_premiere_title',
			'name'            => 'Premiere Product Title',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing->add_field(
		array(
			'id'   => $prefix . 'pricing_premiere_price',
			'name' => 'Premiere Product Price',
			'type' => 'text',
		)
	);

	$pricing->add_field(
		array(
			'id'   => $prefix . 'pricing_premiere_duration',
			'name' => 'Premiere Product Duration',
			'type' => 'text',
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_premiere_subscription_link',
			'name'            => 'Premiere Subscription Link',
			'type'            => 'text_url',
			'sanitization_cb' => false,
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_inside_title',
			'name'            => 'Inside Info Product Title',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing->add_field(
		array(
			'id'   => $prefix . 'pricing_inside_price',
			'name' => 'Inside Info Product Price',
			'type' => 'text',
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_inside_duration',
			'name'            => 'Inside Info Product Duration',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing->add_field(
		array(
			'id'              => $prefix . 'pricing_inside_subscription_link',
			'name'            => 'Inside Subscription Link',
			'type'            => 'text_url',
			'sanitization_cb' => false,
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_members_refund_policy' );
function cgd_members_refund_policy() {
	$prefix = '_cgd_';

	$mem_refund = new_cmb2_box(
		array(
			'id'           => $prefix . 'members_refund_policy_box',
			'title'        => 'Refund Policy Text Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$mem_refund->add_field(
		array(
			'id'              => $prefix . 'members_refund_policy_text',
			'name'            => 'The Refund Policy Text.',
			'type'            => 'textarea_small',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);
}


add_action( 'cmb2_admin_init', 'cgd_register_pricing_features_metaboxes' );
function cgd_register_pricing_features_metaboxes() {
	$prefix = '_cgd_';

	$pricing_features = new_cmb2_box(
		array(
			'id'           => $prefix . 'pricing_table_product_feature_settings',
			'title'        => 'Pricing Table Features',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$product_features = $pricing_features->add_field(
		array(
			'id'          => $prefix . 'product_features_group',
			'type'        => 'group',
			'description' => 'Add the features group.',
			'options'     => array(
				'group_title'   => 'Feature {#}',
				'add_button'    => 'Add Feature',
				'remove_button' => 'Remove Feature',
				'sortable'      => true,
			),
		)
	);

	$pricing_features->add_group_field(
		$product_features,
		array(
			'id'              => 'title',
			'name'            => 'Title',
			'type'            => 'text',
			'sanitization_cb' => 'cgd_sanitize_text_callback',
		)
	);

	$pricing_features->add_group_field(
		$product_features,
		array(
			'id'      => 'products',
			'name'    => 'Products',
			'type'    => 'multicheck',
			'options' => array(
				'regular'  => 'Regular',
				'premiere' => 'Premiere',
				'inside'   => 'Inside Information Combo',
			),
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_group_pricing_metaboxes' );
function cgd_register_group_pricing_metaboxes() {
	$prefix = '_cgd_';

	$group_pricing = new_cmb2_box(
		array(
			'id'           => $prefix . 'group_pricing_settings',
			'title'        => 'Group Pricing Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-members.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$group_pricing->add_field(
		array(
			'id'   => $prefix . 'group_pricing',
			'name' => 'Group Pricing Content',
			'type' => 'wysiwyg',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_speaking_overview_metaboxes' );
function cgd_register_speaking_overview_metaboxes() {
	$prefix = '_cgd_';

	$speaking_overview = new_cmb2_box(
		array(
			'id'           => $prefix . 'speaking_overview_settings',
			'title'        => 'Overview Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-speaking.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$speaking_overview->add_field(
		array(
			'id'   => $prefix . 'speaking_overview_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$speaking_overview->add_field(
		array(
			'id'   => $prefix . 'speaking_overview_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_speaking_testimonial_metaboxes' );
function cgd_register_speaking_testimonial_metaboxes() {
	$prefix = '_cgd_';

	$speaking_testimonials = new_cmb2_box(
		array(
			'id'           => $prefix . 'speaking_testimonials',
			'title'        => 'Testimonial Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-speaking.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$testimonials = $speaking_testimonials->add_field(
		array(
			'id'      => $prefix . 'testimonials_group',
			'name'    => 'Testimonials',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Testimonial {#}',
				'add_button'    => 'Add Another Testimonial',
				'remove_button' => 'Remove Testimonial',
				'sortable'      => true,
			),
		)
	);

	$speaking_testimonials->add_group_field(
		$testimonials,
		array(
			'id'   => 'testimonial',
			'name' => 'Testimonial',
			'type' => 'textarea',
		)
	);

	$speaking_testimonials->add_group_field(
		$testimonials,
		array(
			'id'   => 'author',
			'name' => 'Testimonial author',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_speaking_event_logos_metaboxes' );
function cgd_register_speaking_event_logos_metaboxes() {
	$prefix = '_cgd_';

	$speaking_logos = new_cmb2_box(
		array(
			'id'           => $prefix . 'speaking_event_logo_settings',
			'title'        => 'Speaking Logo Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-speaking.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$speaking_logos->add_field(
		array(
			'id'   => $prefix . 'speaking_logos_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$logos = $speaking_logos->add_field(
		array(
			'id'          => $prefix . 'speaking_logos',
			'type'        => 'group',
			'description' => 'Add the speaking logos.',
			'options'     => array(
				'group_title'   => 'Logo {#}',
				'add_button'    => 'Add Logo',
				'remove_button' => 'Remove Logo',
				'sortable'      => true,
			),
		)
	);

	$speaking_logos->add_group_field(
		$logos,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$speaking_logos->add_group_field(
		$logos,
		array(
			'id'   => 'image',
			'name' => 'Image',
			'type' => 'file',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_speaking_cost_settings_metaboxes' );
function cgd_register_speaking_cost_settings_metaboxes() {
	$prefix = '_cgd_';

	$speaking_cost = new_cmb2_box(
		array(
			'id'           => $prefix . 'speaking_cost_settings',
			'title'        => 'Speaking Cost Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-speaking.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$speaking_cost->add_field(
		array(
			'id'   => $prefix . 'speaking_cost_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$speaking_cost->add_field(
		array(
			'id'   => $prefix . 'speaking_cost_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_speaking_content_settings' );
function cgd_register_speaking_content_settings() {
	$prefix = '_cgd_';

	$speaking_content = new_cmb2_box(
		array(
			'id'           => $prefix . 'speaking_content_settings',
			'title'        => 'Speaking Content Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-speaking.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_content_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_content_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_content_button_link',
			'name' => 'Button Link',
			'type' => 'text',
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_content_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

	$videos = $speaking_content->add_field(
		array(
			'id'          => $prefix . 'speaking_videos',
			'type'        => 'group',
			'description' => 'Add the speaking videos.',
			'options'     => array(
				'group_title'   => 'Video {#}',
				'add_button'    => 'Add Video',
				'remove_button' => 'Remove Video',
				'sortable'      => true,
			),
		)
	);

	$speaking_content->add_group_field(
		$videos,
		array(
			'id'   => 'video',
			'name' => 'Video Link',
			'type' => 'oembed',
		)
	);

	$speaking_content->add_group_field(
		$videos,
		array(
			'id'   => 'desc',
			'name' => 'Video Description',
			'type' => 'textarea',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_speaking_availability_metaboxes' );
function cgd_register_speaking_availability_metaboxes() {
	$prefix = '_cgd_';

	$speaking_content = new_cmb2_box(
		array(
			'id'           => $prefix . 'speaking_availability_settings',
			'title'        => 'Speaking Availability Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-speaking.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_availability_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_availability_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$speaking_content->add_field(
		array(
			'id'   => $prefix . 'speaking_availability_button_link',
			'name' => 'Button Link',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_consulting_pricing_metaboxes' );
function cgd_register_consulting_pricing_metaboxes() {
	$prefix = '_cgd_';

	$consulting_price = new_cmb2_box(
		array(
			'id'           => $prefix . 'consulting_pricing_settings',
			'title'        => 'Consulting Price Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-consulting.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$consulting_price->add_field(
		array(
			'id'   => $prefix . 'consulting_price_desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$services = $consulting_price->add_field(
		array(
			'id'          => $prefix . 'consulting_products',
			'type'        => 'group',
			'description' => 'Add the consulting services.',
			'options'     => array(
				'group_title'   => 'Service {#}',
				'add_button'    => 'Add Service',
				'remove_button' => 'Remove Service',
				'sortable'      => true,
			),
		)
	);

	$consulting_price->add_group_field(
		$services,
		array(
			'id'   => 'icon',
			'name' => 'Icon',
			'desc' => 'Add the Font Awesome icon CSS class.',
			'type' => 'text',
		)
	);

	$consulting_price->add_group_field(
		$services,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$consulting_price->add_group_field(
		$services,
		array(
			'id'   => 'price',
			'name' => 'Price',
			'type' => 'text',
		)
	);

	$consulting_price->add_field(
		array(
			'id'   => $prefix . 'consulting_price_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$consulting_price->add_field(
		array(
			'id'   => $prefix . 'consulting_price_button_url',
			'name' => 'Button URL',
			'type' => 'text_url',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_consulting_logos_metaboxes' );
function cgd_register_consulting_logos_metaboxes() {
	$prefix = '_cgd_';

	$consulting_logos = new_cmb2_box(
		array(
			'id'           => $prefix . 'consulting_logo_settings',
			'title'        => 'Consulting Logo Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-consulting.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$consulting_logos->add_field(
		array(
			'id'   => $prefix . 'consulting_logos_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$logos = $consulting_logos->add_field(
		array(
			'id'          => $prefix . 'consulting_logos',
			'type'        => 'group',
			'description' => 'Add the speaking logos.',
			'options'     => array(
				'group_title'   => 'Logo {#}',
				'add_button'    => 'Add Logo',
				'remove_button' => 'Remove Logo',
				'sortable'      => true,
			),
		)
	);

	$consulting_logos->add_group_field(
		$logos,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$consulting_logos->add_group_field(
		$logos,
		array(
			'id'   => 'image',
			'name' => 'Image',
			'type' => 'file',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_consulting_advisory_metaboxes' );
function cgd_register_consulting_advisory_metaboxes() {
	$prefix = '_cgd_';

	$advisory_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'consulting_advisory_settings',
			'title'        => 'Consulting Advisory Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-consulting.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$advisory_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_advisory_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$advisory_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_current_advisory_board_title',
			'name' => 'Current Advisory Board Title',
			'type' => 'text',
		)
	);

	$advisory_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_former_advisory_board_title',
			'name' => 'Former Advisory Board Title',
			'type' => 'text',
		)
	);

	$current_logos = $advisory_settings->add_field(
		array(
			'id'          => $prefix . 'current_advisory_logos',
			'type'        => 'group',
			'description' => 'Add the current advisory logos.',
			'options'     => array(
				'group_title'   => 'Logo {#}',
				'add_button'    => 'Add Logo',
				'remove_button' => 'Remove Logo',
				'sortable'      => true,
			),
		)
	);

	$advisory_settings->add_group_field(
		$current_logos,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$advisory_settings->add_group_field(
		$current_logos,
		array(
			'id'   => 'image',
			'name' => 'Image',
			'type' => 'file',
		)
	);

	$advisory_settings->add_group_field(
		$current_logos,
		array(
			'id'   => 'desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$former_logos = $advisory_settings->add_field(
		array(
			'id'          => $prefix . 'former_advisory_logos',
			'type'        => 'group',
			'description' => 'Add the former advisory logos.',
			'options'     => array(
				'group_title'   => 'Logo {#}',
				'add_button'    => 'Add Logo',
				'remove_button' => 'Remove Logo',
				'sortable'      => true,
			),
		)
	);

	$advisory_settings->add_group_field(
		$former_logos,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$advisory_settings->add_group_field(
		$former_logos,
		array(
			'id'   => 'image',
			'name' => 'Image',
			'type' => 'file',
		)
	);

	$advisory_settings->add_group_field(
		$former_logos,
		array(
			'id'   => 'desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_consulting_actions_metaboxes' );
function cgd_register_consulting_actions_metaboxes() {
	$prefix = '_cgd_';

	$action_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'consulting_action_settings',
			'title'        => 'Consulting Action Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-consulting.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_action_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_action_desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_advisors_button_text',
			'name' => 'Advisors Button Text',
			'type' => 'text',
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_advisors_button_url',
			'name' => 'Advisors Button URL',
			'type' => 'text_url',
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_consumers_button_text',
			'name' => 'Consumers Button Text',
			'type' => 'text',
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'consulting_consumers_button_url',
			'name' => 'Consumers Button URL',
			'type' => 'text_url',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_advisor_description_metaboxes' );
function cgd_register_advisor_description_metaboxes() {
	$prefix = '_cgd_';

	$action_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'advisor_desc_settings',
			'title'        => 'Description Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-advisors-consumers.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$action_settings->add_field(
		array(
			'id'   => $prefix . 'advisor_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_advisors_nav_icon' );
function cgd_register_advisors_nav_icon() {

	$prefix = '_cgd_';

	$nav_icons = new_cmb2_box(
		array(
			'id'           => $prefix . 'advisors_nav_icon_settings',
			'title'        => 'Navigation Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-advisors-consumers.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$icons = $nav_icons->add_field(
		array(
			'id'          => $prefix . 'advisors_icon_group',
			'type'        => 'group',
			'description' => 'Add the icons for the navigation area.',
			'options'     => array(
				'group_title'   => 'Icon {#}',
				'add_button'    => 'Add Icon',
				'remove_button' => 'Remove Icon',
				'sortable'      => true,
			),
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$nav_icons->add_group_field(
		$icons,
		array(
			'id'   => 'icon',
			'name' => 'Icon',
			'desc' => 'Enter the Font Awesome class of the icon that you want to display. I.E. "fa-user"',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_company_group_metaboxes' );
function cgd_register_company_group_metaboxes() {
	$prefix = '_cgd_';

	$ac_solutions = new_cmb2_box(
		array(
			'id'           => $prefix . 'company_groups',
			'title'        => 'Company Group Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-advisors-consumers.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$company_groups = $ac_solutions->add_field(
		array(
			'id'          => $prefix . 'companies_group',
			'type'        => 'group',
			'description' => __( 'Generates company groups.', 'cmb' ),
			'options'     => array(
				'group_title'   => 'Company Group {#}',
				'add_button'    => 'Add Another Company Group',
				'remove_button' => 'Remove Company Group',
				'sortable'      => true,
			),
		)
	);

	$ac_solutions->add_group_field(
		$company_groups,
		array(
			'id'   => 'company_group_name',
			'name' => 'Company Group Title',
			'type' => 'text',
		)
	);

	$ac_solutions->add_group_field(
		$company_groups,
		array(
			'id'   => 'company_group_id',
			'name' => 'Company Group ID',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_company_metaboxes' );
function cgd_register_company_metaboxes() {
	$prefix = '_cgd_';

	$company_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'companies',
			'title'        => 'Company Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-advisors-consumers.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$company = $company_settings->add_field(
		array(
			'id'          => $prefix . 'company_group',
			'type'        => 'group',
			'description' => __( 'Generates companies.', 'cmb' ),
			'options'     => array(
				'group_title'   => 'Company {#}',
				'add_button'    => 'Add Another Company',
				'remove_button' => 'Remove Company',
				'sortable'      => true,
			),
		)
	);

	$company_settings->add_group_field(
		$company,
		array(
			'id'   => 'company_name',
			'name' => 'Company Title',
			'type' => 'text',
		)
	);

	$company_settings->add_group_field(
		$company,
		array(
			'id'   => 'company_desc',
			'name' => 'Company Description',
			'type' => 'textarea',
		)
	);

	$company_settings->add_group_field(
		$company,
		array(
			'id'   => 'company_logo',
			'name' => 'Company Logo',
			'type' => 'file',
		)
	);

	$company_settings->add_group_field(
		$company,
		array(
			'id'   => 'company_link',
			'name' => 'Company Link',
			'type' => 'text_url',
		)
	);

	$company_settings->add_group_field(
		$company,
		array(
			'id'   => 'company_belongs_to_group_id',
			'name' => 'Company Group ID',
			'desc' => 'Which company group does this company belong to?',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_about_metaboxes' );
function cgd_register_about_metaboxes() {

	$prefix = '_cgd_';

	$about_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'about_settings',
			'title'        => 'About Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-about.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_header_tagline',
			'name' => 'Header Tagline',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_video',
			'name' => 'Video',
			'type' => 'oembed',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_video_desc',
			'name' => 'Video Description',
			'type' => 'textarea',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_education_title',
			'name' => 'Education Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_education_left_content',
			'name' => 'Education Left content',
			'type' => 'wysiwyg',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_education_right_content',
			'name' => 'Education Right Content',
			'type' => 'wysiwyg',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'about_degrees_title',
			'name' => 'Degrees Title',
			'type' => 'text',
		)
	);

	$left_degrees = $about_settings->add_field(
		array(
			'id'      => $prefix . 'about_degrees_left_list',
			'name'    => 'Degrees Left List',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Degree {#}',
				'add_button'    => 'Add Another Degree',
				'remove_button' => 'Remove Degree',
				'sortable'      => true,
			),
		)
	);

	$about_settings->add_group_field(
		$left_degrees,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$right_degrees = $about_settings->add_field(
		array(
			'id'      => $prefix . 'about_degrees_right_list',
			'name'    => 'Degrees Right List',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Degree {#}',
				'add_button'    => 'Add Another Degree',
				'remove_button' => 'Remove Degree',
				'sortable'      => true,
			),
		)
	);

	$about_settings->add_group_field(
		$right_degrees,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'audience_title',
			'name' => 'Audience Section Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'audience_left_content',
			'name' => 'Audience Left Content',
			'type' => 'wysiwyg',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'audience_right_content',
			'name' => 'Audience Right Content',
			'type' => 'wysiwyg',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'numbers_title',
			'name' => 'Numbers Section Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'numbers_degrees',
			'name' => 'Number of Degrees',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'years_experience',
			'name' => 'Years of Experience',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'speaking_engagements',
			'name' => 'Number of Annual Speaking Engagements',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'numbers_members',
			'name' => 'Number of Members',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'numbers_visitors',
			'name' => 'Number of Visitors',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'why_title',
			'name' => 'Why Section Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'why_quote',
			'name' => 'Why Section Quote',
			'type' => 'textarea',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'why_content',
			'name' => 'Why Section Content',
			'type' => 'wysiwyg',
		)
	);

	$why_buttons = $about_settings->add_field(
		array(
			'id'      => $prefix . 'why_buttons_group',
			'name'    => 'Why Buttons',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Button {#}',
				'add_button'    => 'Add Another Button',
				'remove_button' => 'Remove Button',
				'sortable'      => true,
			),
		)
	);

	$about_settings->add_group_field(
		$why_buttons,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$about_settings->add_group_field(
		$why_buttons,
		array(
			'id'   => 'text',
			'name' => 'Text',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'ideas_title',
			'name' => 'Ideas Section Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'ideas_content',
			'name' => 'Ideas Section Content',
			'type' => 'wysiwyg',
		)
	);

	$idea_buttons = $about_settings->add_field(
		array(
			'id'   => $prefix . 'idea_buttons_group',
			'name' => 'Why Buttons',
			'type' => 'group',
		)
	);

	$about_settings->add_group_field(
		$idea_buttons,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$about_settings->add_group_field(
		$idea_buttons,
		array(
			'id'   => 'text',
			'name' => 'Text',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'background_title',
			'name' => 'Background Section Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'background_content',
			'name' => 'Background Section Content',
			'type' => 'wysiwyg',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'background_image',
			'name' => 'Background Section Image',
			'type' => 'file',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'sleep_title',
			'name' => 'Sleep Section Title',
			'type' => 'text',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'sleep_content',
			'name' => 'Sleep Section Content',
			'type' => 'wysiwyg',
		)
	);

	$about_settings->add_field(
		array(
			'id'   => $prefix . 'sleep_image',
			'name' => 'Sleep Section image',
			'type' => 'file',
		)
	);

	$sleep_buttons = $about_settings->add_field(
		array(
			'id'      => $prefix . 'sleep_buttons_group',
			'name'    => 'Sleep Buttons',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Button {#}',
				'add_button'    => 'Add Another Button',
				'remove_button' => 'Remove Button',
				'sortable'      => true,
			),
		)
	);

	$about_settings->add_group_field(
		$sleep_buttons,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$about_settings->add_group_field(
		$sleep_buttons,
		array(
			'id'   => 'text',
			'name' => 'Text',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_contact_metaboxes' );
function cgd_register_contact_metaboxes() {
	$prefix = '_cgd_';

	$contact_page_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'contact_page_settings',
			'title'        => 'Contact Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-contact.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$contact_page_settings->add_field(
		array(
			'id'   => $prefix . 'contact_page_left_content',
			'name' => 'Left Content',
			'type' => 'wysiwyg',
		)
	);

	$contact_page_settings->add_field(
		array(
			'id'   => $prefix . 'contact_page_right_content',
			'name' => 'Right Content',
			'type' => 'wysiwyg',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_books_metaboxes' );
function cgd_register_books_metaboxes() {
	$prefix = '_cgd_';

	$book_group_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'book_group_titles',
			'title'        => 'Book Group Title Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-recommended-reading.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$book_groups = $book_group_settings->add_field(
		array(
			'id'      => 'book_groups',
			'name'    => 'Book Groups',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Book Group {#}',
				'add_button'    => 'Add Book Group',
				'remove_button' => 'Remove Book Group',
				'sortable'      => true,
			),
		)
	);

	$book_group_settings->add_group_field(
		$book_groups,
		array(
			'id'   => 'book_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$book_group_settings->add_group_field(
		$book_groups,
		array(
			'id'   => 'book_group_id',
			'name' => 'Group ID',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_book_metaboxes' );
function cgd_register_book_metaboxes() {
	$prefix = '_cgd_';

	$book_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'book_settings',
			'title'        => 'Book Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-recommended-reading.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$books = $book_settings->add_field(
		array(
			'id'      => 'books',
			'name'    => 'Books',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Book {#}',
				'add_button'    => 'Add Book',
				'remove_button' => 'Remove Book',
				'sortable'      => true,
			),
		)
	);

	$book_settings->add_group_field(
		$books,
		array(
			'id'   => 'book_image',
			'name' => 'Image',
			'type' => 'file',
		)
	);

	$book_settings->add_group_field(
		$books,
		array(
			'id'   => 'book_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$book_settings->add_group_field(
		$books,
		array(
			'id'   => 'book_link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$book_settings->add_group_field(
		$books,
		array(
			'id'   => 'book_author',
			'name' => 'Author',
			'type' => 'text',
		)
	);

	$book_settings->add_group_field(
		$books,
		array(
			'id'   => 'book_belongs_to_group_id',
			'name' => 'Group ID',
			'desc' => 'Which Group does this book belong to?',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_media_info_metaboxes' );
function cgd_register_media_info_metaboxes() {
	$prefix = '_cgd_';

	$media_info_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'media_info_settings',
			'title'        => 'Media Information Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-media.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$media_info_settings->add_field(
		array(
			'id'   => $prefix . 'media_info_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$media_info_settings->add_field(
		array(
			'id'   => $prefix . 'media_info_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_media_appearances_metaboxes' );
function cgd_register_media_appearances_metaboxes() {
	$prefix = '_cgd_';

	$media_appearances_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'media_appearances_settings',
			'title'        => 'Media Appearance Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-media.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$media_appearances_settings->add_field(
		array(
			'id'   => $prefix . 'media_appearances_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$media_appearances_settings->add_field(
		array(
			'id'   => $prefix . 'media_appearances_button_link',
			'name' => 'Button Link',
			'type' => 'text_url',
		)
	);

	$media_appearances_settings->add_field(
		array(
			'id'   => $prefix . 'media_appearances_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);

	$appearance_logos = $media_appearances_settings->add_field(
		array(
			'id'      => $prefix . 'media_logos_group',
			'name'    => 'Appearance Logos',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Logo {#}',
				'add_button'    => 'Add Logo',
				'remove_button' => 'Remove Button',
				'sortable'      => true,
			),
		)
	);

	$media_appearances_settings->add_group_field(
		$appearance_logos,
		array(
			'id'   => 'logo',
			'name' => 'Logo Image',
			'type' => 'file',
		)
	);

	$media_appearances_settings->add_group_field(
		$appearance_logos,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_media_nav_icons' );
function cgd_register_media_nav_icons() {

	$prefix = '_cgd_';

	$media_nav_idons = new_cmb2_box(
		array(
			'id'           => $prefix . 'media_nav_icon_settings',
			'title'        => 'Navigation Section Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-media.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$icons = $media_nav_idons->add_field(
		array(
			'id'          => $prefix . 'media_icon_group',
			'type'        => 'group',
			'description' => 'Add the icons for the navigation area.',
			'options'     => array(
				'group_title'   => 'Icon {#}',
				'add_button'    => 'Add Icon',
				'remove_button' => 'Remove Icon',
				'sortable'      => true,
			),
		)
	);

	$media_nav_idons->add_group_field(
		$icons,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$media_nav_idons->add_group_field(
		$icons,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$media_nav_idons->add_group_field(
		$icons,
		array(
			'id'   => 'icon',
			'name' => 'Icon',
			'desc' => 'Enter the Font Awesome class of the icon that you want to display. I.E. "fa-user"',
			'type' => 'text',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_media_expertise_metaboxes' );
function cgd_register_media_expertise_metaboxes() {

	$prefix = '_cgd_';

	$expertise_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'media_expertise_settings',
			'title'        => 'Expertise Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-media.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$expertise_settings->add_field(
		array(
			'id'   => $prefix . 'expertise_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$expertise_settings->add_field(
		array(
			'id'   => $prefix . 'expertise_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_regiser_media_attribution_metaboxes' );
function cgd_regiser_media_attribution_metaboxes() {

	$prefix = '_cgd_';

	$attribution_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'media_attribution_settings',
			'title'        => 'Attribution Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-media.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$attribution_settings->add_field(
		array(
			'id'   => $prefix . 'attribution_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$attribution_settings->add_field(
		array(
			'id'   => $prefix . 'attribution_left_content',
			'name' => 'Left Content',
			'type' => 'wysiwyg',
		)
	);

	$attribution_settings->add_field(
		array(
			'id'   => $prefix . 'attribution_right_content',
			'name' => 'Right Content',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_media_assets_metaboxes' );
function cgd_register_media_assets_metaboxes() {

	$prefix = '_cgd_';

	$asset_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'media_asset_settings',
			'title'        => 'Asset Settings',
			'object_types' => array( 'page' ),
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-media.php',
			),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$asset_settings->add_field(
		array(
			'id'   => $prefix . 'asset_bio_title',
			'name' => 'Bio Title',
			'type' => 'text',
		)
	);

	$asset_settings->add_field(
		array(
			'id'   => $prefix . 'asset_bio_content',
			'name' => 'Bio Content',
			'type' => 'wysiwyg',
		)
	);

	$asset_settings->add_field(
		array(
			'id'   => $prefix . 'asset_photo_title',
			'name' => 'Photo Title',
			'type' => 'text',
		)
	);

	$asset_settings->add_field(
		array(
			'id'   => $prefix . 'asset_photos_content',
			'name' => 'Photos Content',
			'type' => 'wysiwyg',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_ce_badge_metaboxes' );
function cgd_register_ce_badge_metaboxes() {

	$prefix = '_kitces_';

	$ce_badge_settings = new_cmb2_box(
		array(
			'id'           => $prefix . 'ce_badge_settings',
			'title'        => 'Single Post Settings',
			'object_types' => array( 'post' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$ce_badge_settings->add_field(
		array(
			'id'   => $prefix . 'show_ce_banner',
			'name' => 'Show CE Banner',
			'type' => 'checkbox',
		)
	);

	$ce_badge_settings->add_field(
		array(
			'id'   => $prefix . 'show_ce_ethics_banner',
			'name' => 'Show CE Ethics Banner',
			'type' => 'checkbox',
			'desc' => 'Must also have "Show CE Banner" checked.',
		)
	);

	$ce_badge_settings->add_field(
		array(
			'id'   => $prefix . 'show_ce_old_banner',
			'name' => 'Show Outdated CE Message',
			'type' => 'checkbox',
			'desc' => 'Must also have "Show CE Banner" checked.',
		)
	);

	$ce_badge_settings->add_field(
		array(
			'id'   => $prefix . 'custom_thrive_shortcode',
			'name' => 'Display custom shortcode.',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_tab_page_metaboxes' );
function cgd_register_tab_page_metaboxes() {
	$prefix = '_cgd_';

	$best_categories = new_cmb2_box(
		array(
			'id'           => $prefix . 'best_of_cat_group',
			'title'        => 'Category Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-best-of.php',
			),
		)
	);

	$categories = $best_categories->add_field(
		array(
			'id'      => $prefix . 'cat_group',
			'name'    => 'Categories',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Category {#}',
				'add_button'    => 'Add Another Category',
				'remove_button' => 'Remove Category',
				'sortable'      => true,
			),
		)
	);

	$best_categories->add_group_field(
		$categories,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$best_categories->add_group_field(
		$categories,
		array(
			'id'   => 'id',
			'name' => 'Category ID',
			'type' => 'text',
		)
	);

	$best_posts = new_cmb2_box(
		array(
			'id'           => $prefix . 'best_of_cat_group',
			'title'        => 'Category Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-best-of.php',
			),
		)
	);

	$posts = $best_posts->add_field(
		array(
			'id'      => $prefix . 'posts_group',
			'name'    => 'Posts',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Post {#}',
				'add_button'    => 'Add Another Post',
				'remove_button' => 'Remove Post',
				'sortable'      => true,
			),
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'guest',
			'name' => 'Is this a guest post?',
			'type' => 'checkbox',
		)
	);

	$best_posts->add_group_field(
		$posts,
		array(
			'id'   => 'id',
			'name' => 'Category ID',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_presentations_metaboxes' );
function cgd_register_presentations_metaboxes() {
	$prefix = '_cgd_';

	$presentations_cats = new_cmb2_box(
		array(
			'id'           => $prefix . 'presentations_categories',
			'title'        => 'Presentation Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-presentations.php',
			),
		)
	);

	$categories = $presentations_cats->add_field(
		array(
			'id'      => $prefix . 'presentations_categories_group',
			'name'    => 'Categories',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Category {#}',
				'add_button'    => 'Add Another Category',
				'remove_button' => 'Remove Category',
				'sortable'      => true,
			),
		)
	);

	$presentations_cats->add_group_field(
		$categories,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$presentations_cats->add_group_field(
		$categories,
		array(
			'id'   => 'id',
			'name' => 'Category ID',
			'type' => 'text',
		)
	);

	$presentations_posts = new_cmb2_box(
		array(
			'id'           => $prefix . 'presentation_posts',
			'title'        => 'Presentations',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-presentations.php',
			),
		)
	);

	$presentations = $presentations_posts->add_field(
		array(
			'id'      => $prefix . 'presentations_group',
			'name'    => 'Presentations',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Presentation {#}',
				'add_button'    => 'Add Another Presentation',
				'remove_button' => 'Remove Presentation',
				'sortable'      => true,
			),
		)
	);

	$presentations_posts->add_group_field(
		$presentations,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$presentations_posts->add_group_field(
		$presentations,
		array(
			'id'   => 'label',
			'name' => 'Label',
			'type' => 'text',
		)
	);

	$presentations_posts->add_group_field(
		$presentations,
		array(
			'id'   => 'ce-eligible',
			'name' => 'CE Eligible',
			'type' => 'checkbox',
		)
	);

	$presentations_posts->add_group_field(
		$presentations,
		array(
			'id'   => 'desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$presentations_posts->add_group_field(
		$presentations,
		array(
			'id'   => 'category_id',
			'name' => 'Category ID',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_conference_metaboxes' );
function cgd_register_conference_metaboxes() {
	$prefix = '_cgd_';

	$conference_attachments = new_cmb2_box(
		array(
			'id'           => $prefix . 'conference_attachments',
			'title'        => 'Attachments',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-conference-landing-page.php',
			),
		)
	);

	$conference_attachments->add_field(
		array(
			'id'   => $prefix . 'conference_attachments_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$conference_attachments->add_field(
		array(
			'id'   => $prefix . 'conference_attachments_desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$files = $conference_attachments->add_field(
		array(
			'id'      => $prefix . 'conference_attachments_group',
			'name'    => 'Files',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'File {#}',
				'add_button'    => 'Add Another File',
				'remove_button' => 'Remove File',
				'sortable'      => true,
			),
		)
	);

	$conference_attachments->add_group_field(
		$files,
		array(
			'id'   => 'file',
			'name' => 'File',
			'type' => 'file',
		)
	);

	$conference_attachments->add_group_field(
		$files,
		array(
			'id'   => 'image',
			'name' => 'Image',
			'type' => 'file',
		)
	);

	$conference_reading_materials = new_cmb2_box(
		array(
			'id'           => $prefix . 'conference_reading_materials',
			'title'        => 'Reading Materials Settings (OLD - DO NOT USE ON NEW PAGES)',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-conference-landing-page.php',
			),
		)
	);

	$conference_reading_materials->add_field(
		array(
			'id'   => $prefix . 'conference_reading_materials_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$conference_reading_materials->add_field(
		array(
			'id'   => $prefix . 'conference_reading_materials_desc',
			'name' => 'Description',
			'type' => 'textarea',
		)
	);

	$materials = $conference_reading_materials->add_field(
		array(
			'id'      => $prefix . 'conference_reading_materials_group',
			'name'    => 'Reading Materials',
			'type'    => 'group',
			'options' => array(
				'group_title'   => 'Post {#}',
				'add_button'    => 'Add Another Post',
				'remove_button' => 'Remove Post',
				'sortable'      => true,
			),
		)
	);

	$conference_reading_materials->add_group_field(
		$materials,
		array(
			'id'   => 'title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$conference_reading_materials->add_group_field(
		$materials,
		array(
			'id'   => 'link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$conference_thrive_optin = new_cmb2_box(
		array(
			'id'           => $prefix . 'conference_thrive_optin',
			'title'        => 'After Post OptIn',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-conference-landing-page.php',
			),
		)
	);

	$conference_thrive_optin->add_field(
		array(
			'id'   => $prefix . 'conference_oim_optin_shortcode',
			'name' => 'OIM Optin Shortcode',
			'desc' => 'Overide the global OIM Optin shortcode set in the theme settings... (Applies to this page only.)',
			'type' => 'text',
		)
	);

}

add_action( 'cmb2_admin_init', 'cgd_register_testimonials_posttype_metaboxes' );
function cgd_register_testimonials_posttype_metaboxes() {
	$prefix = '_cgd_';

	$testimonial_posttype_metaboxes = new_cmb2_box(
		array(
			'id'           => $prefix . 'posttype_testimonials',
			'title'        => 'Testimonial Settings',
			'object_types' => array( 'testimonials' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
		)
	);

	$testimonial_posttype_metaboxes->add_field(
		array(
			'id'   => $prefix . 'testmionial_posttype_text',
			'name' => 'Text',
			'type' => 'textarea',
		)
	);

	$testimonial_posttype_metaboxes->add_field(
		array(
			'id'   => $prefix . 'testimonial_posttype_link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$testimonial_posttype_metaboxes->add_field(
		array(
			'id'   => $prefix . 'testimonial_posttype_sidebar_image',
			'name' => 'Widget Image',
			'desc' => 'Upload the image that you would like to display in the sidebar.',
			'type' => 'file',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_ce_purchase_credits_box_metaboxes' );
function cgd_register_ce_purchase_credits_box_metaboxes() {
	$prefix = '_cgd_';

	$purchase_credits = new_cmb2_box(
		array(
			'id'           => $prefix . 'purchase_credits_box_settings',
			'title'        => 'Purchase Credits Box Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-ce-credit.php',
			),
		)
	);

	$purchase_credits->add_field(
		array(
			'id'   => $prefix . 'purchase_credits_box_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$purchase_credits->add_field(
		array(
			'id'   => $prefix . 'purchase_credits_box_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

	$purchase_credits->add_field(
		array(
			'id'   => $prefix . 'purchase_credits_box_link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$purchase_credits->add_field(
		array(
			'id'   => $prefix . 'purchase_credits_box_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_ce_become_member_box_metaboxes' );
function cgd_register_ce_become_member_box_metaboxes() {
	$prefix = '_cgd_';

	$ce_become_member = new_cmb2_box(
		array(
			'id'           => $prefix . 'ce_become_member_settings',
			'title'        => 'Become a Member Box Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-ce-credit.php',
			),
		)
	);

	$ce_become_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_box_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$ce_become_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_box_desc',
			'name' => 'Description',
			'type' => 'wysiwyg',
		)
	);

	$ce_become_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_box_link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$ce_become_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_box_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);
}

add_action( 'cmb2_admin_init', 'cgd_register_ce_already_a_member_box_metaboxes' );
function cgd_register_ce_already_a_member_box_metaboxes() {
	$prefix = '_cgd_';

	$ce_become_already_member = new_cmb2_box(
		array(
			'id'           => $prefix . 'ce_already_settings',
			'title'        => 'Already a Member Box Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-ce-credit.php',
			),
		)
	);

	$ce_become_already_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_already_box_title',
			'name' => 'Title',
			'type' => 'text',
		)
	);

	$ce_become_already_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_already_box_link',
			'name' => 'Link',
			'type' => 'text_url',
		)
	);

	$ce_become_already_member->add_field(
		array(
			'id'   => $prefix . 'ce_become_member_already_box_button_text',
			'name' => 'Button Text',
			'type' => 'text',
		)
	);
}

// Meta Boxes for the Contact Page
add_action( 'cmb2_admin_init', 'cgd_register_contact_kitces_metaboxes' );
function cgd_register_contact_kitces_metaboxes() {
	$prefix = '_cgd_';

	$contact_kitces = new_cmb2_box(
		array(
			'id'           => $prefix . 'contact_page_settings_2016',
			'title'        => 'Contact Page Settings',
			'object_types' => array( 'page' ),
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'show_on'      => array(
				'key'   => 'page-template',
				'value' => 'template-contact-2016.php',
			),
		)
	);

	$contact_kitces->add_field(
		array(
			'id'      => $prefix . 'tweet_me_img',
			'name'    => 'The image that will be used behind the Tweet Me section.',
			'type'    => 'file',
			'options' => array(
				'url' => false, // Hide the text input for the url
			),
		)
	);

	$contact_kitces->add_field(
		array(
			'id'   => $prefix . 'left_of_form_content',
			'name' => 'Content to the Left of the Contact Form',
			'type' => 'wysiwyg',
		)
	);

	$contact_kitces->add_field(
		array(
			'id'   => $prefix . 'the_gravity_form_content',
			'name' => 'Add the Shortcode of the Gravity Form to Display',
			'type' => 'wysiwyg',
		)
	);

	$contact_kitces->add_field(
		array(
			'id'   => $prefix . 'other_contact_first',
			'name' => 'Add the text for the first Other Contact Method',
			'type' => 'wysiwyg',
		)
	);

	$contact_kitces->add_field(
		array(
			'id'   => $prefix . 'other_contact_second',
			'name' => 'Add the text for the second Other Contact Method',
			'type' => 'wysiwyg',
		)
	);

	$contact_kitces->add_field(
		array(
			'id'   => $prefix . 'other_contact_third',
			'name' => 'Add the text for the third Other Contact Method',
			'type' => 'wysiwyg',
		)
	);
}
