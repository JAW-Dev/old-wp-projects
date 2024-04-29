<?php

/*
Template Name: Page List
*/

remove_action( 'genesis_after_header', 'mg_breadcrumb_bar' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cgd_timeline_header' );
function cgd_timeline_header() {

	$allpageargs = array(
		'sort_order'   => 'asc',
		'sort_column'  => 'post_title',
		'hierarchical' => 1,
		'exclude'      => '',
		'include'      => '',
		'meta_key'     => '',
		'meta_value'   => '',
		'authors'      => '',
		'child_of'     => 0,
		'parent'       => -1,
		'exclude_tree' => '',
		'number'       => '',
		'offset'       => 0,
		'post_type'    => 'page',
		'post_status'  => 'publish,private,draft',
	);
	$pages       = get_pages( $allpageargs );
	$templates   = array();

	?>
	<section style="max-width: 500px; margin-left: auto; margin-right: auto; margin-top: 2em;">

		<h2>All Pages</h2>

		<ul>
			<?php foreach ( $pages as $page ) : ?>

				<?php
				$id        = $page->ID;
				$title     = $page->post_title;
				$permalink = get_the_permalink( $id );
				$template  = get_page_template_slug( $id );

				if ( ! empty( $template ) && ! in_array( $template, $templates ) ) {
					array_push( $templates, $template );
				}

				?>
				<li>
					<a href="<?php echo $permalink; ?>"><?php echo $title; ?></a> - <?php echo $template; ?>
				</li>

			<?php endforeach; ?>
		</ul>
	</section>

	<!-- Templates Used -->
	<section style="max-width: 500px; margin-left: auto; margin-right: auto; margin-top: 2em;">
		<h2>Templates Used</h2>

		<?php sort( $templates ); ?>

		<ul>
			<?php foreach ( $templates as $t ) : ?>
				<li><?php echo $t; ?></li>
			<?php endforeach; ?>
		</ul>
	</section>

	<!-- Adding More Endpoint Links -->
	<?php

	$post_types         = get_post_types();
	$exclude_post_types = array(
		'attachment',
		'revision',
		'nav_menu_item',
		'custom_css',
		'customize_changeset',
		'envira',
		'envira_album',
		'soliloquy',
		'deleted_event',
	);

	?>

	<section style="max-width: 500px; margin-left: auto; margin-right: auto; margin-top: 2em;">

		<h2>All The Left Overs Linked</h2>

		<?php foreach ( $post_types as $spt ) : ?>
			<?php if ( ! in_array( $spt, $exclude_post_types ) ) : ?>
				<?php
					$sptargs   = array(
						'posts_per_page' => -1,
						'order'          => 'asc',
						'orderby'        => 'post_title',
						'post_type'      => $spt,
						'post_status'    => 'publish',
					);
					$spt_posts = get_posts( $sptargs );
					?>
				<h4 style="margin-top: 32px;"><?php echo $spt; ?> - <?php echo count( $spt_posts ); ?></h4>
				<?php if ( ! empty( $spt_posts ) ) : ?>
					<ul>
						<?php foreach ( $spt_posts as $spt_single ) : ?>
							<?php
								$the_id    = $spt_single->ID;
								$the_title = get_the_title( $the_id );
								$the_perm  = get_the_permalink( $the_id );
							?>
							<li>
								<a href="<?php echo $the_perm; ?>"><?php echo $the_title; ?></a>
							</li>
						<?php endforeach; ?>
					</ul>
				<?php endif; ?>
			<?php endif; ?>
		<?php endforeach; ?>

	</section>

	<?php
}

genesis();
