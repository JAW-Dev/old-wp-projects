<?php

function objectiv_do_podcast_reviews_slider( $reviews = null ) {
	if ( is_array( $reviews ) ) {
		echo "<div class='podcast-reviews-slider-outer'>";
		echo "<div class='podcast-reviews-slider-inner'>";
		foreach ( $reviews as $review ) {
			objectiv_do_podcast_review( $review );
		}
		echo '</div>';

		$reviews_check = $reviews && ( is_array( $reviews ) || is_object( $reviews ) ) && $reviews > 1;

		if ( $reviews_check ) {
			if ( count( $reviews ) ) {
				echo "<div class='podcast-reviews-slider-arrow-wrap'>";
				echo "<div class='left-arrow'>";
				slide_arrow();
				echo '</div>';
				echo "<div class='right-arrow'>";
				slide_arrow();
				echo '</div>';
				echo '</div>';
			}
		}
		echo '</div>';
	}
}

function objectiv_do_podcast_review( $review = null ) {
	$title       = mk_key_value( $review, 'title' );
	$stars       = mk_key_value( $review, 'stars' );
	$content     = mk_key_value( $review, 'content' );
	$attribution = mk_key_value( $review, 'attribution' );
	$display     = ! empty( $title ) && ! empty( $stars ) && ! empty( $attribution ) && ! empty( $content );

	if ( $display ) {
		echo "<div class='podcast-review-slide tac'>";
		echo "<div class='fwb f22'>$title</div>";
		objectiv_do_podcast_stars( $stars );
		echo "<div class='f20 mt1'>$content</div>";
		echo "<div class='fwb tc-text-med-blue mt1'>$attribution</div>";
		echo '</div>';
	}
}

function objectiv_do_podcast_stars( $stars = null ) {
	if ( ! empty( $stars ) ) {
		echo "<div class='podcast-review-stars mt1'>";
		$stars = (int) $stars;
		$a     = 1;
		while ( $a <= $stars ) {
			?>
			<svg width="20px" height="20px" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
				<g id="Symbols" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<g id="Icon---Star--Filled" transform="translate(-1.000000, -1.000000)" fill="#56A3D9" fill-rule="nonzero">
						<g id="Star-Icon" transform="translate(1.000000, 1.000000)">
							<polygon id="Shape" points="20.0034095 7.63950449 12.7150813 7.63950449 10.002273 0 7.28719173 7.63950449 0 7.63950449 5.94613024 12.1252415 3.81975224 20 10.002273 15.279009 16.1825207 20 14.0584157 12.1252415"></polygon>
						</g>
					</g>
				</g>
			</svg>
			<?php
			$a++;
		}
		echo '</div>';
	}
}

function objectiv_do_podcast_reviews_section( $title = null, $reviews = null ) {
	if ( ! empty( $title ) || ! empty( $reviews ) ) {
	?>
	<section class="page-section reviews-section spt spb">
		<div class="wrap">
			<div class="reviews-section-inner">
				<?php if ( ! empty( $title ) ) : ?>
					<h2 class="section-title tac"><?php echo $title; ?></h2>
				<?php endif; ?>
				<?php
				if ( ! empty( $reviews ) ) :
					objectiv_do_podcast_reviews_slider( $reviews );
				endif;
				?>
			</div>
		</div>
	</section>
	<?php
	}
}
