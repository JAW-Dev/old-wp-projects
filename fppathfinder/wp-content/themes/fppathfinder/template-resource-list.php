<?php

/*
Template Name: Resource List
*/

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_filter( 'body_class', 'objectiv_body_class' );
function objectiv_body_class( $classes ) {

	$classes[] = 'resource-list';
	return $classes;

}

// Remove 'site-inner' from structural wrap
add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'footer-widgets', 'footer' ) );

add_action( 'objectiv_page_content', 'page_content' );
function page_content() {
	$resources = get_field( 'fp_resource_list' );
	$button    = get_field( 'fp_resource_button_text' );
	?>
	<div class="template-resource-list-content-outer resource-list wrap">
		<div class="resource-list__header">
			<img class="resource-list__header-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/assets/images/logo.svg"/>
			<h2 class="resource-list__header-text">Summary Resource Guide</h2>
		</div>
		<div class="resource-list__body">
			<?php
			foreach ( $resources as $resource ) {
				$cat_ids = $resource['categories'];
				$posts   = get_resource_posts( $cat_ids );
				?>
					<div class="resource-list__body-section">
						<h3 class="resource-list__body-heading">
							<?php echo esc_html( $resource['title'] ); ?>:
						</h3>
						<?php
						foreach ( $posts as $post ) {
							?>
							<p class="resource-list__body-item">-&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $post; ?></p>
							<?php
						}
						?>
					</div>
					<?php
			}
			?>
		</div>
		<button class="resource-list__button button" onclick="window.print();return false;"><?php echo esc_html( $button ); ?></button>
	</div>
	<?php
}

function get_resource_posts( $cat_ids ) {
	$posts = array();

	foreach ( $cat_ids as $cat_id ) {
		$args = array(
			'post_type'      => 'resource',
			'posts_per_page' => -1,
			'tax_query'      =>
				array(
					array(
						'taxonomy' => 'resource-cat',
						'field'    => 'term_id',
						'terms'    => $cat_id,
					),
				),
			'fields'         => 'ids',
		);

		$query = new WP_Query( $args );

		foreach ( $query->posts as $id ) {
			$primary = get_post_meta( $id, '_yoast_wpseo_primary_resource-cat', true );

			if ( (int) $primary === (int) $cat_id ) {
				$title   = esc_html( get_the_title( $id ) );
				$link    = get_permalink( $id );
				$link    = "<a href=\"{$link}\">{$title}</a>";
				$posts[] = $link;
			}
		}
	}

	return $posts;
}

get_header();
do_action( 'objectiv_page_content' );
get_footer();
