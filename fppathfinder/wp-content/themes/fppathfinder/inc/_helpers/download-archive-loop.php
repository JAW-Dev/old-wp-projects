<?php

function download_archive_loop() {
	$memb_link = get_post_type_archive_link( 'download' );

	if ( ! empty( $memb_link ) && is_tax( 'download-cat' ) ) { ?>
		<span class="button download-cat-button small-button">
			<a href="<?php echo esc_url( $memb_link ); ?>">Back to All Downloads</a>
		</span>
	<?php
	}

	if ( have_posts() ) :
		do_action( 'genesis_before_while' );
		echo "<div class='download-loop-outer-wrap'>";
		// objectiv_before_downloads_filter();
		?>
		<form action="#" class="download-filter-form basemb2">
			<input type="text" class="download-filter-input" placeholder="Filter Downloads - Begin Typing...">
		</form>
		<?php
		echo "<div class='download-loop-inner-wrap'>";
		while ( have_posts() ) :
			the_post();
			$id = get_the_ID();

			do_download_archive_block( $id );

		endwhile; // End of one post.
		echo '</div>';
		echo '</div>';
		do_action( 'genesis_after_endwhile' );

	else : // If no posts exist.
		do_action( 'genesis_loop_else' );
	endif; // End loop.
}

function do_download_archive_block( $id = null ) {
	if ( ! empty( $id ) ) {
		$title         = get_the_title( $id );
		$perm          = get_permalink( $id );
		$blurb         = objectiv_get_short_description( $id, 40 );
		$levels        = get_post_rcp_levels( $id );
		$level_classes = array();
		if ( ! empty( $levels ) ) {
			foreach ( $levels as $l ) {
				array_push( $level_classes, 'level_' . $l );
			}
			$level_classes = implode( ' ', $level_classes );
		} else {
			$level_classes = 'free';
		}
		?>
		<div class="download-archive-block all <?php echo $level_classes; ?>">
			<a href="<?php echo $perm; ?>">
				<div class="download-archive-block-inner">
					<header class="download-block__header">
						<h4 class="download-block__title"><?php echo esc_html( $title ); ?></h4>
						<div class="download-block__details">
							<?php objectiv_download_member_deets( $id ); ?>
						</div>
					</header>
					<div class="download-block__blurb">
						<?php echo esc_html( $blurb ); ?>
					</div>
				</div>
			</a>
		</div>
		<?php
	}
}

function objectiv_before_downloads_filter() {
	$levels = get_all_rcp_levels();

	if ( ! empty( $levels ) ) {
		echo "<div class='downloads-filter-bar'>";
		echo "<span class='filter-title'>";
		echo 'Filter:';
		echo '</span>';
		objectiv_download_filter_link( 'All', 'all current', 'all' );
		objectiv_download_filter_link( 'Free', 'free', 'free' );
		foreach ( $levels as $level ) {
			$name      = $level->name;
			$class     = 'level_' . $level->id;
			$data_attr = 'level_' . $level->id;
			objectiv_download_filter_link( $name, $class, $data_attr );
		}
		echo '</div>';
	}
}


function objectiv_download_filter_link( $title = null, $class = null, $data_attr = null ) {
?>
	<?php if ( ! empty( $title ) && ! empty( $class ) ) : ?>
		<div class="filter-link <?php echo $class; ?>" data-filter-class="<?php echo $data_attr; ?>"><?php echo $title; ?></div>
	<?php endif; ?>
<?php

}


function objectiv_download_member_deets( $post_id = null ) {
	if ( ! empty( $post_id ) ) {
		$levels   = get_post_rcp_levels( $post_id );
		$unlocked = false;

		if ( rcp_user_can_access( get_current_user_id(), $post_id ) ) {
			$unlocked = true;
		}

		if ( ! empty( $levels ) ) {
			foreach ( $levels as $l ) {
				$l_class = 'level_' . $l;
				echo "<div class='level-block level_all $l_class'>";
				echo '</div>';
			}
		}
		if ( $unlocked ) {
			obj_svg( 'pad-unlocked' );
		} else {
			obj_svg( 'pad-locked' );
		}
	}
}
