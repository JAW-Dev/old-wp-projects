<?php

// Custom taxonomy for Content Type for CC Items
if ( ! function_exists( 'objectiv_custom_tax_content_type' ) ) {

	// Register Custom Taxonomy
	function objectiv_custom_tax_content_type() {

		$labels = array(
			'name'                       => _x( 'Content Types', 'Taxonomy General Name', 'text_domain' ),
			'singular_name'              => _x( 'Content Type', 'Taxonomy Singular Name', 'text_domain' ),
			'menu_name'                  => __( 'Content Types', 'text_domain' ),
			'all_items'                  => __( 'All Content Types', 'text_domain' ),
			'parent_item'                => __( 'Parent Content Type', 'text_domain' ),
			'parent_item_colon'          => __( 'Parent Content Type:', 'text_domain' ),
			'new_item_name'              => __( 'New Content Type Name', 'text_domain' ),
			'add_new_item'               => __( 'Add New Content Type', 'text_domain' ),
			'edit_item'                  => __( 'Edit Content Type', 'text_domain' ),
			'update_item'                => __( 'Update Content Type', 'text_domain' ),
			'view_item'                  => __( 'View Content Type', 'text_domain' ),
			'separate_items_with_commas' => __( 'Separate states with commas', 'text_domain' ),
			'add_or_remove_items'        => __( 'Add or remove states', 'text_domain' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'text_domain' ),
			'popular_items'              => __( 'Popular Content Types', 'text_domain' ),
			'search_items'               => __( 'Search Content Types', 'text_domain' ),
			'not_found'                  => __( 'Not Found', 'text_domain' ),
			'no_terms'                   => __( 'No states', 'text_domain' ),
			'items_list'                 => __( 'Content Types list', 'text_domain' ),
			'items_list_navigation'      => __( 'Content Types list navigation', 'text_domain' ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy( 'cc-item-content-type', array( 'cc-item' ), $args );

	}
	add_action( 'init', 'objectiv_custom_tax_content_type', 0 );

}


function cgd_cc_item_add_taxonomy_filters() {
	global $typenow;

	// an array of all the taxonomyies you want to display. Use the taxonomy name or slug
	$taxonomies = array( 'cc-item-content-type' );

	// must set this to the post type you want the filter(s) displayed on
	if ( $typenow == 'cc-item' ) {

		foreach ( $taxonomies as $tax_slug ) {
			$tax_obj  = get_taxonomy( $tax_slug );
			$tax_name = $tax_obj->labels->name;
			$terms    = get_terms( $tax_slug );
			if ( count( $terms ) > 0 ) {
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>Show All $tax_name</option>";
				foreach ( $terms as $term ) {
					echo '<option value=' . $term->slug, $_GET[ $tax_slug ] == $term->slug ? ' selected="selected"' : '','>' . $term->name . ' (' . $term->count . ')</option>';
				}
				echo '</select>';
			}
		}
	}
}
add_action( 'restrict_manage_posts', 'cgd_cc_item_add_taxonomy_filters' );
