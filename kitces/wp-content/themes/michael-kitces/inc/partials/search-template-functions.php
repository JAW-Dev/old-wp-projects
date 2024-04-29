<?php

function cgd_search() { ?>
	<div class="search-page-form">
		<h3>What would you like to search for?</h3>
		<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
			<input type="search" class="search-field" placeholder="Search Nerd's Eye View" value="" name="s" title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
			<button type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>">
				<i class="fas fa-search"></i>
			</button>
		</form>
	</div>
    <?php
}

function cgd_search_links() { ?>
    <div class="search-recent-posts one-half first">
		<h3>You might also be interested in some of our recent articles...</h3>
		<?php $args = array(
			'posts_per_page' => 5,
			'order' => 'DESC',
		); ?>
		<?php $related_posts = new WP_Query( $args ); ?>
		<ul>
		<?php if( $related_posts->have_posts() ): ?>
			<?php while ( $related_posts->have_posts() ): $related_posts->the_post(); ?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php wp_reset_postdata(); ?>
			<?php endwhile; ?>
		<?php else: ?>
			<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
		<?php endif; ?>
	</div>
	<div class="search-related-cats one-half">
		<h3>...And can find our content by category too!</h3>
		<ul>
			<?php wp_list_categories('orderby=count&title_li='); ?>
		</ul>
	</div>
    <?php
}
