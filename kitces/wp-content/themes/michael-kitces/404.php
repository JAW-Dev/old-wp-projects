<?php

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_after_header', 'cgd_404_header' );
function cgd_404_header() { ?>
    <section class="page-hero">
    	<h1 class="page-hero-title">Page Not Found</h1>
		<p class="page-hero-desc">Whoops, you tried to access http://<?php echo $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] ?> and it doesn't seem to exist.</p>
    </section>
<?php }

add_action( 'genesis_loop', 'cgd_404_page' );
function cgd_404_page() {
	?>
	<div class="search-404">
		<div class="search-404-form two-thirds first">
			<h3>Why don't you try searching for what you are looking for?</h3>
			<?php echo get_search_form(); ?>
		</div>
		<div class="search-404-member one-third">
			<p>If you were looking for a page in our members section, please login or register.</p>
			<a href="/login" class="button button-blue">Login</a>
			<a href="/become-a-member/" class="button">Become a Member</a>
		</div>
	</div>
	<?php
}

add_action( 'genesis_loop', 'cgd_404_more_links' );
function cgd_404_more_links() { ?>
	<div class="related-links-404">
		<div class="recent-posts-404 one-half first">
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
				<?php endwhile; ?>
			<?php else: ?>
				<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
			<?php endif; ?>
		</div>
		<div class="related-categories-404 one-half">
			<h3>...And can find our content by category too!</h3>
			<ul>
				<?php wp_list_categories('orderby=count&title_li='); ?>
			</ul>
		</div>
	</div>
<?php }

genesis();
