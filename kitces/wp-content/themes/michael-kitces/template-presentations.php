<?php

/*
Template Name: Presentations Page
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'tabs-page';
	return $classes;

}
// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_after_loop', 'cgd_tabs' );
function cgd_tabs() { ?>
	<?php
	$prefix = '_cgd_';
	$categories = get_post_meta( get_the_ID(), $prefix . 'presentations_categories_group', true );
	$posts = get_post_meta( get_the_ID(), $prefix . 'presentations_group', true );
	?>
	<div id="tabs">
		<ul class="resp-tabs-list">
			<li>All</li>
			<?php foreach( $categories as $group ): ?>
				<li><?php echo $group['title']; ?></li>
			<?php endforeach; ?>
		</ul>

		<div class="resp-tabs-container">
			<div class="tab-presentations">
				<h2>All</h2>
				<?php foreach( $posts as $post ): ?>
					<div class="tab-presentation">
						<h3><?php echo $post['title']; ?></h3>
						<?php if ( ! empty( $post['label'] ) ): ?>
							<span class="tab-label"><?php echo $post['label']; ?></span>
						<?php endif; ?>
						<?php if (! empty($post['ce-eligible']) && $post['ce-eligible'] === "on" ) : ?>
							<span class="tab-label ce-label">CE Eligible</span>
						<?php endif; ?>
						<p><?php echo $post['desc']; ?></p>
					</div>
				<?php endforeach; ?>
			</div>
			<?php foreach( $categories as $group ): ?>
				<div class="tab-presentations">
					<h2><?php echo $group['title']; ?></h2>
					<?php foreach( $posts as $post ): ?>
						<?php if ( $post['category_id'] == $group['id'] ): ?>
							<div class="tab-presentation">
								<h3><?php echo $post['title']; ?></h3>
								<?php if ( ! empty( $post['label'] ) ): ?>
									<span class="tab-label"><?php echo $post['label']; ?></span>
								<?php endif; ?>
								<?php if (!empty($post['ce-eligible']) && $post['ce-eligible'] === "on") : ?>
									<span class="tab-label ce-label">CE Eligible</span>
								<?php endif; ?>
								<p><?php echo $post['desc']; ?></p>
							</div>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php }

genesis();
