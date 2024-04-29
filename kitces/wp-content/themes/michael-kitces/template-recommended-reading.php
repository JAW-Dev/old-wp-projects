<?php

/*
Template Name: Recommended Reading
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'recommended-reading';
	return $classes;

}

remove_action( 'genesis_loop', 'genesis_do_loop' );

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_after_header', 'cgd_book_tabs' );
function cgd_book_tabs() { ?>
	<?php
	$prefix = '_cgd_';
	$book_groups = get_post_meta( get_the_ID(), 'book_groups', true );
	$books = get_post_meta( get_the_ID(), 'books', true );
	?>
	<section class="content-section" style="background: #fff;">
		<div class="wrap">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

					<?php do_action( 'genesis_before_entry' ); ?>

					<?php printf( '<article %s>', genesis_attr( 'entry' ) ); ?>

						<?php do_action( 'genesis_entry_header' ); ?>

						<?php do_action( 'genesis_before_entry_content' ); ?>
						<?php printf( '<div %s>', genesis_attr( 'entry-content' ) ); ?>
							<?php do_action( 'genesis_entry_content' ); ?>
						<?php echo '</div>'; //* end .entry-content ?>
						<?php do_action( 'genesis_after_entry_content' ); ?>

						<?php do_action( 'genesis_entry_footer' ); ?>

					<?php echo '</article>'; ?>

					<?php do_action( 'genesis_after_entry' ); ?>

				<?php endwhile; //* end of one post ?>
				<?php do_action( 'genesis_after_endwhile' ); ?>

			<?php else : //* if no posts exist ?>
				<?php do_action( 'genesis_loop_else' ); ?>
			<?php endif; //* end loop ?>
			<div id="book-tabs">
		        <ul class="resp-tabs-list">
					<?php foreach( $book_groups as $group ): ?>
						<li><?php echo $group['book_title']; ?></li>
					<?php endforeach; ?>
		        </ul>

		        <div class="resp-tabs-container">
					<?php foreach( $book_groups as $group ): ?>
						<?php $count = 1; ?>
						<div>
							<h2><?php echo $group['book_title']; ?></h2>
							<?php foreach( $books as $book ): ?>
								<?php if ( $book['book_belongs_to_group_id'] == $group['book_group_id'] ): ?>
									<?php
									if ( $count % 2 == 0 ) {
										$class = '';
									} else {
										$class = 'first';
									}
									?>
									<div class="book one-half <?php echo $class; ?>">
										<div class="book-image one-third first">
											<a href="<?php echo $book['book_link']; ?>" target="_blank">
												<img src="<?php echo $book['book_image']; ?>" alt="<?php echo $book['book_title']; ?>" />
											</a>
										</div>
										<div class="book-info two-thirds">
											<a href="<?php echo $book['book_link']; ?>" target="_blank">
												<p class="book-title">"<?php echo $book['book_title']; ?>"</p>
											</a>
											<p class="book-author">by <?php echo $book['book_author']; ?></p>
										</div>
									</div>
									<?php $count++; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					<?php endforeach; ?>
		        </div>
		    </div>
		</div>
	</section>
<?php }

genesis();
