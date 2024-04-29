<?php

function popular_tabs_func( $atts ) {
	$prefix     = '_cgd_';
	$categories = get_post_meta( get_the_ID(), $prefix . 'pop_cat_group', true );
	$posts      = get_post_meta( get_the_ID(), $prefix . 'pop_posts_group', true );
	ob_start();
	?>
	<div id="tabs">
		<ul class="resp-tabs-list">
			<?php foreach ( $categories as $group ): ?>
				<li><?php echo esc_html( $group['title'] ); ?></li>
			<?php endforeach; ?>
		</ul>

		<div class="resp-tabs-container">
			<?php foreach ( $categories as $group ): ?>
				<?php $count = 1; ?>
				<div>
					<h2><?php echo esc_html( $group['title'] ); ?></h2>
					<ul>
						<?php
						foreach ( $posts as $post ) :
							if ( $post['id'] === $group['id'] ) :
								$post_guest = ! empty( $post['guest'] ) ? $post['guest'] : '';
								$guest_post = 'on' === $post_guest ? '(Guest Post)' : '';
								?>
								<li><a href="<?php echo esc_url( $post['link'] ); ?>" target="_blank"><?php echo esc_html( $post['title'] ); ?></a> <?php echo esc_html( $guest_post ); ?></li>
								<?php
							endif;
						endforeach;
						?>
					</ul>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'tabs', 'popular_tabs_func' );
