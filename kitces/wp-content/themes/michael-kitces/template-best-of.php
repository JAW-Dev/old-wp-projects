<?php

/*
Template Name: Best Of Posts Page
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'tabs-page';
	return $classes;

}

add_action( 'genesis_after_loop', 'cgd_tabs' );
function cgd_tabs() { ?>
	<?php
	$prefix = '_cgd_';
	$categories = get_post_meta( get_the_ID(), $prefix . 'cat_group', true );
	$posts = get_post_meta( get_the_ID(), $prefix . 'posts_group', true );
	?>
	<div id="tabs">
		<ul class="resp-tabs-list">
			<li>All</li>
			<?php foreach( $categories as $group ): ?>
				<li><?php echo $group['title']; ?></li>
			<?php endforeach; ?>
		</ul>

		<div class="resp-tabs-container">
			<div>
				<h2>All</h2>
				<?php foreach( $posts as $post ): ?>
					<ul>
						<li><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a> <?php if ( $post['guest'] == 'on' ) { echo '(Guest Post)'; } ?></li>
					</ul>
				<?php endforeach; ?>
			</div>
			<?php foreach( $categories as $group ): ?>
				<?php $count = 1; ?>
				<div>
					<h2><?php echo $group['title']; ?></h2>
					<?php foreach( $posts as $post ): ?>
						<?php if ( $post['id'] == $group['id'] ): ?>
							<ul>
								<li><a href="<?php echo $post['link']; ?>"><?php echo $post['title']; ?></a> <?php if ( $post['guest'] == 'on' ) { echo '(Guest Post)'; } ?></li>
							</ul>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
<?php }

genesis();
