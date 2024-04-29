<?php

remove_action( 'genesis_before_loop', 'genesis_do_taxonomy_title_description', 15 );
add_action( 'genesis_after_header', 'cgd_category_banner' );
function cgd_category_banner() {
	$cat   = get_the_category();
	$title = get_term_meta( $cat[0]->term_id, 'headline', true );
	?>
	<section class="page-hero">
		<?php if ( $title ) : ?>
			<h1 class="page-hero-title"><?php echo $title; ?></h1>
		<?php else : ?>
			<h1 class="page-hero-title">Articles About <?php echo $cat[0]->name; ?></h1>
		<?php endif; ?>
	</section>
	<?php
}

// remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_post_info', 12 );
add_action( 'genesis_entry_header', 'kitces_entry_header' );

genesis();
