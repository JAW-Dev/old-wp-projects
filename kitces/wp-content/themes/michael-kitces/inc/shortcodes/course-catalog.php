<?php

function cgd_course_catalog( $atts ) {

	if ( ! empty( $atts && array_key_exists( 'single', $atts ) ) ) {
		$cc_single = $atts['single'];
	} else {
		$cc_single = false;
	}

	if ( ! empty( $atts && array_key_exists( 'id', $atts ) ) ) {
		$cc_single_id = $atts['id'];
	} else {
		$cc_single_id = null;
	}

	if ( ! empty( $atts && array_key_exists( 'banners', $atts ) ) ) {
		$cc_banners = boolval( $atts['banners'] );
	} else {
		$cc_banners = false;
	}

	if ( ! empty( $atts && array_key_exists( 'filter', $atts ) ) ) {
		$cc_filter = boolval( $atts['filter'] );
	} else {
		$cc_filter = false;
	}

	// Get all of the custom taxonomies
	$cont_types = get_terms(
		array(
			'taxonomy'   => 'cc-item-content-type',
			'hide_empty' => true,
		)
	);

	$all_cat_items = true;
	if ( ! empty( $cc_single ) && $cc_single == 'true' ) {
		$all_cat_items = false;
	}

	ob_start(); ?>

	<?php if ( ! empty( $cont_types ) && $all_cat_items ) : ?>
		<div class="course-catalog-section-wrap">
			<?php cgd_cc_filter_list( $cc_filter, false, null, true ); ?>
			<?php
			if ( ! empty( $cont_types ) ) {
				foreach ( $cont_types as $ct ) {
					// Output the title of the section
					?>
					<h2 class="cont-type-title"><?php echo $ct->name; ?></h2>
					<?php

					// Get all of the catalog items for this content type
					$args      = array(
						'posts_per_page' => -1,
						'post_type'      => 'cc-item',
						'orderby'        => 'date',
						'order'          => 'DESC',
						'tax_query'      => array(
							array(
								'taxonomy'    => 'cc-item-content-type',
								'field'       => 'term_id',
								'terms'       => $ct->term_id,
								'post_status' => 'publish',
							),
						),
					);
					$cat_items = get_posts( $args );
					global $course_catalog_post;
					?>
					<div class="course-catalog-section">
						<div class="accordion-block">
							<?php if ( ! empty( $cat_items ) ) : ?>
								<?php
								foreach ( $cat_items as $c ) :
									$course_catalog_post = $c;
									?>
									<?php cgd_cci_row( $c->ID, $c->post_title, $cc_banners ); ?>
								<?php endforeach; ?>
							<?php endif; ?>
                            <div class="no-results">No results...</div>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
	<?php elseif ( ! $all_cat_items && ! empty( $cc_single ) && $cc_single == 'true' && ! empty( $cc_single_id ) ) : ?>
		<div class="course-catalog-section-wrap">
			<?php
				$single_terms = wp_get_post_terms( $cc_single_id, 'cc-item-content-type' );
				$first_term   = $single_terms[0]->name;
				$single_title = get_the_title( $cc_single_id );
				global $course_catalog_post;
				$course_catalog_post = get_post( $cc_single_id );
			?>
			<?php if ( ! empty( $first_term ) ) : ?>
				<h2 class="cont-type-title"><?php echo $first_term; ?></h2>
			<?php endif; ?>
			<div class="course-catalog-section">
				<div class="accordion-block">
					<?php cgd_cci_row( $cc_single_id, $single_title ); ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
	return ob_get_clean();

}

add_shortcode( 'course-catalog', 'cgd_course_catalog' );

// Get buddy working
function cgd_cci_row( $id, $item_title, $banners = false ) {
	$cci_availability_title    = get_field( 'catalog_availability_title', 'options' );
	$cci_availability_text     = get_field( 'catalog_availability_text', 'options' );
	$associated_quiz_page_link = null;
	$associated_quiz_page_id   = cgd_get_cci_associated_quiz_page( $id );
	$quiz_cat_string           = null;

	if ( $associated_quiz_page_id ) {
		$associated_quiz_page_link = get_permalink( $associated_quiz_page_id );
		$quiz_cat_string           = cgd_get_cci_quiz_cat_string( $associated_quiz_page_id );
		$quiz_type_string          = cgd_get_cci_quiz_type_string( $associated_quiz_page_id );
	}

	?>
		<div class="accordion-row <?php echo $quiz_cat_string; ?> <?php echo $quiz_type_string; ?>">
			<div class="accordion-title transition_3sec">
				<div class="title"><?php echo $item_title; ?></div>
				<div class="svg-wrap">
					<svg class="icon-plus" enable-background="new 0 0 100 100" id="Layer_1" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<polygon class="plus_fill" fill="#a6a6a6" points="80.2,51.6 51.4,51.6 51.4,22.6 48.9,22.6 48.9,51.6 19.9,51.6 19.9,54.1 48.9,54.1 48.9,83.1   51.4,83.1 51.4,54.1 80.4,54.1 80.4,51.6 "/>
					</svg>
				</div>
			</div>
			<div class="accordion-info">
				<?php if ( $banners ) : ?>
					<?php cgd_cci_ce_banner( $associated_quiz_page_link, 'mb1', $associated_quiz_page_id ); ?>
				<?php endif; ?>
				<?php
				// check if the flexible content field has rows of data
				if ( have_rows( 'catalog_flexible_sections', $id ) ) :

					// loop through the rows of data
					while ( have_rows( 'catalog_flexible_sections', $id ) ) :
						the_row();
							$title   = get_sub_field( 'title' );
							$content = get_sub_field( 'content' );

						if ( ! empty( $title ) && ! empty( $content ) ) {
							cgd_cci_content( $title, $content );
						}
					endwhile;

					if ( ! empty( $cci_availability_text ) ) {
						echo "<div class='catalog-item-section'>";
						if ( ! empty( $cci_availability_title ) ) {
							echo "<h3 class='catalog-item__title'>$cci_availability_title</h3>";
						}
						echo "<div class='catalog-item__content'>";
						echo $cci_availability_text;
						echo '</div>';
						echo '</div>';
					}
				endif;
				?>
				<?php if ( $banners ) : ?>
					<?php cgd_cci_ce_banner( $associated_quiz_page_link, 'mb1', $associated_quiz_page_id ); ?>
				<?php endif; ?>
		</div>
	</div>
	<?php
}

function cgd_cci_content( $title, $content ) {
	?>
	<div class="catalog-item-section">
		<?php if ( ! empty( $title ) ) : ?>
			<h3 class="catalog-item__title"><?php echo $title; ?>:</h3>
		<?php endif; ?>

		<?php if ( ! empty( $content ) ) : ?>
			<div class="catalog-item__content">
				<?php echo $content; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

function cgd_cci_ce_banner( $associated_quiz_page_link = null, $class = null, $quiz_page_id = null ) {
	$post_has_permissions = members_has_post_roles( $quiz_page_id );
	$has_access_to_quiz   = is_user_logged_in();

	if ( $post_has_permissions ) {
		$has_access_to_quiz = is_user_logged_in() && kitces_member_can_access_post( $quiz_page_id, get_current_user_id() );
	}

	?>
	<div class="ce-banner <?php echo $class; ?>">
		<div class="ce-banner-content">
			<div class="first-side">
				<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g stroke="#000" stroke-linejoin="round" fill="none" fill-rule="evenodd"><path d="M17 8.5l-7.5 7L7 13" stroke-linecap="round"/><path d="M23.5 12c0-.979-.705-1.79-1.634-1.962a2.002 2.002 0 00.759-2.438 1.999 1.999 0 00-2.262-1.189 1.998 1.998 0 00-.233-2.543 2 2 0 00-2.542-.233 1.996 1.996 0 00-3.627-1.502 1.996 1.996 0 00-3.926 0 1.996 1.996 0 00-3.627 1.502 2 2 0 00-2.542.234 1.998 1.998 0 00-.233 2.542 1.995 1.995 0 00-1.503 3.626 1.996 1.996 0 000 3.926 1.997 1.997 0 001.503 3.627 2 2 0 00.233 2.542 2 2 0 002.542.235 1.998 1.998 0 003.627 1.501A2 2 0 0012 23.5c.979 0 1.788-.703 1.961-1.632a1.999 1.999 0 002.438.757 1.998 1.998 0 001.189-2.26 1.997 1.997 0 002.775-2.775 2 2 0 002.262-1.188 2 2 0 00-.759-2.439A1.999 1.999 0 0023.5 12h0z"/></g></svg>
				<h3>Want to get this CE Credit?</h3>
			</div>
			<?php if ( $has_access_to_quiz ) : ?>
				<?php if ( ! empty( $associated_quiz_page_link ) ) : ?>
					<a class="button button-small" href="<?php echo esc_url( $associated_quiz_page_link ); ?>">Take Quiz Now</a>
				<?php endif; ?>
			<?php else : ?>
				<a class="button button-small" href="/become-member-for-imca-ce-and-cfp-ce-credits/#pricing-table-section">Learn More!</a>
			<?php endif; ?>

		</div>
	</div>
	<?php
}

function cgd_get_cci_quiz_cat_string( $associated_quiz_page_id = null ) {
	$cat_string = null;

	if ( ! empty( $associated_quiz_page_id ) ) {
		$cats = wp_get_post_categories( $associated_quiz_page_id );

		if ( ! empty( $cats ) ) {
			foreach ( $cats as $cat ) {
				$cat_string .= ' qc-' . $cat;
			}
		}
	}

	return $cat_string;
}

function cgd_get_cci_quiz_type_string( $associated_quiz_page_id = null ) {
	$type_class_string = null;

	if ( ! empty( $associated_quiz_page_id ) ) {
        global $CGD_CECredits; // phpcs:ignore
        $cecredits = $CGD_CECredits; // phpcs:ignore
		$page      = get_post( $associated_quiz_page_id );
		preg_match( '@id="(\d+)"@', $page->post_content, $matches );

		if ( ! empty( $matches ) && $cecredits->form_is_quiz( array( 'id' => $matches[1] ) ) ) {
			$form        = GFAPI::get_form( $matches[1] );
			$cfp_hours   = rgar( $form, 'hours' ) !== '' ? rgar( $form, 'hours' ) : 0;
			$nasba_hours = rgar( $form, 'nasba_hours' ) !== '' ? rgar( $form, 'nasba_hours' ) : 0;
			$ea_hours    = rgar( $form, 'ea_hours' ) !== '' ? rgar( $form, 'ea_hours' ) : 0;

			if ( floatval( $cfp_hours ) > 0 ) {
				$type_class_string .= ' qt-cfp qt-ac qt-iwi';
			}

			if ( floatval( $nasba_hours ) > 0 ) {
				$type_class_string .= ' qt-nasba';
			}

			if ( floatval( $ea_hours ) > 0 ) {
				$type_class_string .= ' qt-ea';
			}
		}
	}

	return $type_class_string;
}

function cgd_cci_topics() {
	global $course_catalog_post;
	$associated_quiz_id = cgd_get_cci_associated_quiz_page( $course_catalog_post->ID );
	$display            = false;
	$cats               = null;

	if ( ! empty( $associated_quiz_id ) ) {
		$cats = wp_get_post_categories( $associated_quiz_id );

		if ( ! empty( $cats ) ) {
			$display = true;
		}
	}

	ob_start();
	?>

	<?php if ( $display ) : ?>
		<ul>
			<?php foreach ( $cats as $cat ) : ?>
				<?php $name = get_cat_name( $cat ); ?>
				<?php if ( ! empty( $name ) ) : ?>
					<li><?php echo $name; ?></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<?php
	return ob_get_clean();

}
add_shortcode( 'cci_topics', 'cgd_cci_topics' );

function cgd_get_cci_associated_quiz_page( $ccid ) {
	$associated_quiz_page = get_posts(
		array(
			'numberposts' => 1,
			'post_type'   => 'page',
			'meta_key'    => 'course_catalog_item',
			'meta_value'  => $ccid,
		)
	);

	if ( is_array( $associated_quiz_page ) ) {
		return $associated_quiz_page[0]->ID;
	} else {
		return false;
	}
}

function cgd_cc_filter_list( $cc_filter = false, $return = false, $categories = null, $type_filter = false ) {

	if ( $cc_filter ) {
		if ( empty( $categories ) ) {
			$categories = cgd_get_all_quiz_categories();
		}

		if ( ! empty( $categories ) ) {
			if ( $return ) {
				ob_start();
			}
			?>
			<div class="courses-filter-outer-wrap hidden">
				<div class="courses-filter-wrap">
					<div class="filter-label">
						Filter courses by:
					</div>
					<select class="quiz-cat-filter-select" multiple>
						<option data-placeholder="true"></option>
						<?php foreach ( $categories as $cat ) : ?>
							<option value="qc-<?php echo $cat->term_id; ?>"><?php echo $cat->name; ?></option>
						<?php endforeach; ?>
					</select>
					<?php if ( $type_filter ) : ?>
						<select class="quiz-type-filter-select" multiple>
							<option data-placeholder="true"></option>
							<option value="qt-ac">American College</option>
							<option value="qt-cfp">CFP</option>
							<option value="qt-iwi">CIMA/CPWA (IWI)</option>
							<option value="qt-nasba">CPA</option>
							<option value="qt-ea">EA</option>
						</select>
					<?php endif; ?>
				</div>
			</div>
			<?php
			if ( $return ) {
				return ob_get_clean();
			}
		}
	}
}

function cgd_get_all_quiz_categories() {
	$args = array(
		'posts_per_page' => -1,
		'post_type'      => 'cc-item',
	);

	$all_cci = get_posts( $args );
	$cats    = array();

	foreach ( $all_cci as $cci ) {
		$associated_quiz_id = cgd_get_cci_associated_quiz_page( $cci->ID );
		$post_cats          = get_the_terms( $associated_quiz_id, 'category' );

		if ( ! empty( $post_cats ) && is_array( $post_cats ) ) {
			foreach ( $post_cats as $cat ) {
				if ( ! in_array( $cat, $cats, true ) ) {
					$cats[ $cat->name ] = $cat;
				}
			}
		}
	}

	ksort( $cats );

	return $cats;
}
