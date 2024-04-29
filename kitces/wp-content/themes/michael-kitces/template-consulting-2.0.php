<?php

/*
Template Name: Consulting 2.0
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {
	$classes[] = 'consulting2';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

add_action( 'genesis_after_header', 'objectiv_consulting_2_page_content' );

function objectiv_consulting_2_page_content() {
	// Output the header
	objectiv_intro_header();
	objectiv_consult_content_testimonial();
	objectiv_how_mk_can_help();
	objectiv_who_we_serve();
	objectiv_our_consultants();
	objectiv_consulting_packages();
	objectiv_kind_words();
	objectiv_companies_cosulted_with();
	// objectiv_companies_advisor_role();
	objectiv_consulting_content_form();
	objectiv_kind_words( $position = 'last' );
}

function objectiv_intro_header() {
	$head_details = get_field( 'hero_section_details' );
	hero_head( $head_details );
}

function objectiv_consult_content_testimonial() {
	$left_title = get_field( 'left_title' );
	$left_desc  = get_field( 'left_content' );
	$right_test = get_field( 'right_testimonial' );
	$rt_content = $right_test['testimonial_content'];

	if ( ! empty( $left_title ) && ! empty( $left_desc ) ) { ?>
	<section class="page-section left-content-right-test spt spb">
		<div class="wrap">
			<div class="lc-rt-content">
				<div class="left-content">
					<h2 class="left-content-title fwb border0 mb1 f36"><?php echo $left_title; ?></h2>
					<div class="left-content-content f20">
						<?php echo $left_desc; ?>
					</div>
				</div>
				<?php if ( ! empty( $rt_content ) ) : ?>
					<div class="right-content bg-light-blue bpa2 wt">
						<div class="right-content-testimonial f20">
							<?php echo $rt_content; ?>
						</div>
						<?php objectiv_consult_testimonial_footer( $right_test ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
		<?php
	}
}

function objectiv_how_mk_can_help() {
	$section_title = get_field( 'hh_section_title' );
	$top_content   = get_field( 'hh_top_content' );
	$table_details = get_field( 'hh_table_details' );
	$l_text        = get_field( 'hh_large_text' );
	$bot_content   = get_field( 'hh_bottom_content' );
	$lbutton       = get_field( 'hh_left_button' );
	$rbutton       = get_field( 'hh_right_button' );

	if ( ! empty( $section_title ) || ! empty( $top_content ) ) {
		?>
		<section class="how-mk-help page-section bg-light-gray spb spt">
			<div class="wrap">
				<div class="how-mk-content mw-970 mlra">
					<?php if ( ! empty( $section_title ) ) : ?>
						<header class="how-mk-header">
							<h2 class="section-title border0 tac fwb mb2 f36"><?php echo $section_title; ?></h2>
						</header>
					<?php endif; ?>
					<?php if ( ! empty( $top_content ) ) : ?>
						<div class="how-mk-top f20">
							<?php echo $top_content; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $table_details ) ) : ?>
						<table class="how-mk-table mt2 mb2">
							<tr class="how-mk-table-row title-row bg-medium-blue text-white">
								<td class="title"></td>
								<td class="details bpa">Consulting Services</td>
							</tr>
							<?php foreach ( $table_details as $tr ) : ?>
								<?php
								$title   = mk_key_value( $tr, 'row_title' );
								$details = mk_key_value( $tr, 'row_details' );
								?>
								<tr class="how-mk-table-row">
									<td class="title fwb bg-light-blue bpa f20"><?php echo $title; ?></td>
									<td class="details bpa"><?php echo $details; ?></td>
								</tr>
							<?php endforeach; ?>
						</table>
					<?php endif; ?>
					<?php if ( ! empty( $l_text ) ) : ?>
						<div class="how-mk-large-text fwb f36 tac lh1 mt4 mb2 montserrat">
							<?php echo $l_text; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $bot_content ) ) : ?>
						<div class="how-mk-bot-content f20">
							<?php echo $bot_content; ?>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $lbutton ) || ! empty( $rbutton ) ) : ?>
						<footer class="how-me-footer tac">
							<?php if ( ! empty( $lbutton ) ) : ?>
								<?php echo mk_link_html( $lbutton, 'button light-blue' ); ?>
							<?php endif; ?>
							<?php if ( ! empty( $rbutton ) ) : ?>
								<?php echo mk_link_html( $rbutton, 'button light-blue' ); ?>
							<?php endif; ?>
						</footer>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

function objectiv_who_we_serve() {
	$section_title = get_field( 'wws_section_title' );
	$section_copy  = get_field( 'wws_section_copy' );
	$icons         = get_field( 'wws_icons' );
	?>
	<section class="page-section spt spb">
		<div class="wrap">
			<div class="mw-970 mlra">
				<?php if ( ! empty( $section_title ) ) : ?>
					<header class="">
						<h2 class="section-title border0 tac fwb mb2 f36"><?php echo $section_title; ?></h2>
					</header>
				<?php endif; ?>
				<?php if ( ! empty( $icons ) && is_array( $icons ) ) : ?>
					<div class="icon-outer-wrap mb2 mt2">
						<?php foreach ( $icons as $icon ) : ?>
							<div class="">
								<?php
									$image     = mk_key_value( $icon, 'icon' );
									$image_url = mk_key_value( $image, 'url' );
									$title     = mk_key_value( $icon, 'title' );
								?>
								<div class="icon-wrapper">
									<img src="<?php echo $image_url; ?>" alt="">
								</div>
								<div class="icon-block-title f22 mthalf"><?php echo $title; ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $section_copy ) ) : ?>
					<div class="f20">
						<?php echo $section_copy; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_our_consultants() {
	$sec_title   = get_field( 'cons_title' );
	$sec_blurb   = get_field( 'cons_blurb' );
	$consultants = get_field( 'cons_consultants' );

	?>
	<section class="page-section consulting-consultants spt spb bg-light-gray" id="speakers">
		<div class="wrap">
			<div class="mw-970 mlra">
				<?php if ( ! empty( $sec_title ) ) : ?>
					<h2 class="section-title tac"><?php echo $sec_title; ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $sec_blurb ) ) : ?>
					<div class="page-section__content norm-list lmb0"><?php echo $sec_blurb; ?></div>
				<?php endif; ?>
				<?php objectiv_do_new_consultants_table( $consultants ); ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_consulting_packages() {
	$section_title = get_field( 'cp_section_title' );
	$blocks        = get_field( 'cp_package_blocks' );
	$foot_title    = get_field( 'cp_footer_title' );
	$foot_blurb    = get_field( 'cp_footer_blurb' );

	if ( ! empty( $blocks ) ) {
		?>
		<section class="page-section consulting-packages-section spt spb">
			<div class="wrap">
				<?php if ( ! empty( $section_title ) ) : ?>
					<header class="consulting-packages-header">
						<h2 class="section-title border0 tac fwb mb2 f36"><?php echo $section_title; ?></h2>
					</header>
				<?php endif; ?>
				<?php if ( ! empty( $blocks ) ) : ?>
					<div class="consulting-packages-blocks-wrap one23grid">
						<?php
						foreach ( $blocks as $block ) :
							$title         = $block['title'];
							$blurb         = $block['blurb'];
							$cost          = $block['cost'];
							$button        = $block['button'];
							$modal_content = $block['modal_content'];
							$modal_link    = $block['modal_link'];
							$id            = uniqid();

							if ( empty( $modal_link ) ) {
								$modal_link = 'See List of Deliverables';
							}
							?>
							<div class="consulting-block bg-medium-blue">
								<?php if ( ! empty( $title ) ) : ?>
									<h4 class="consulting-block-title wt f24 fwb"><?php echo $title; ?></h4>
								<?php endif; ?>
								<?php if ( ! empty( $blurb ) ) : ?>
									<div class="consulting-block-blurb wt f20 mb1"><?php echo $blurb; ?></div>
								<?php endif; ?>
								<div class="consulting-block-footer">
									<?php if ( ! empty( $modal_content ) ) : ?>
										<a class="wt consult-modal block mb1 lh1" href="#<?php echo $id; ?>"><?php echo $modal_link; ?></a>
										<div id="<?php echo $id; ?>" class="hidden">
											<div class="norm-list">
												<?php if ( ! empty( $title ) ) : ?>
													<h4 class="consulting-block-title f24 fwb tac"><?php echo $title; ?></h4>
												<?php endif; ?>
												<div class="last-child-margin-bottom-0">
													<?php echo $modal_content; ?>
												</div>
												<div class="modal-footer tac">
													<div class="modaal-close button button-small">
														<div class="button button-small">
															Close
														</div>
													</div>
												</div>
											</div>
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $cost ) ) : ?>
										<div class="consulting-block-cost wt f20 fwb mb1 lh1"><?php echo $cost; ?></div>
									<?php endif; ?>
									<?php if ( ! empty( $button ) ) : ?>
										<div class="consulting-block-button-wrap"><?php echo mk_link_html( $button, 'button' ); ?></div>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $foot_title ) || ! empty( $foot_blurb ) ) : ?>
					<footer class="consulting-packages-footer tac mt2">
						<?php if ( ! empty( $foot_title ) ) : ?>
							<h5 class="consulting-footer-title fwb f30"><?php echo $foot_title; ?></h5>
						<?php endif; ?>
						<?php if ( ! empty( $foot_blurb ) ) : ?>
							<div class="consulting-footer-blurb ml0 f22"><?php echo $foot_blurb; ?></div>
						<?php endif; ?>
					</footer>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

function objectiv_kind_words( $position = null ) {

	if ( $position === 'last' ) {
		$section_title     = get_field( 'last_test_section_title' );
		$testimonials      = get_field( 'last_testimonials' );
		$slider_class      = 'kind-words-slider-last';
		$slider_wrap_class = 'kind-words-slider-wrap-last';
		$bg_class          = '';

	} else {
		$section_title     = get_field( 'kind_section_title' );
		$testimonials      = get_field( 'kind_testimonials' );
		$slider_class      = 'kind-words-slider';
		$slider_wrap_class = 'kind-words-slider-wrap';
		$bg_class          = 'bg-light-gray';

	}

	if ( ! empty( $testimonials ) ) {
		?>
		<section class="page-section kind-words spt spb <?php echo $bg_class; ?>">
			<div class="wrap">
				<?php if ( ! empty( $section_title ) ) : ?>
					<h2 class="section-title kind-words-section-title tac fwb f36 border0 mb2"><?php echo $section_title; ?></h2>
				<?php endif; ?>
				<div class="<?php echo $slider_wrap_class; ?>">
					<div class="<?php echo $slider_class; ?>">
						<?php
						foreach ( $testimonials as $t ) :
							$t_content = $t['testimonial_content'];
							?>
							<?php if ( ! empty( $t_content ) ) : ?>
							<div class="kind-words-slide tac">
								<div class="kind-words-slide-content">
									<?php echo $t_content; ?>
								</div>
								<div class="kind-words-slide-footer-wrap">
									<?php objectiv_consult_testimonial_footer( $t ); ?>
								</div>
							</div>
						<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<?php if ( count( $testimonials ) > 1 ) : ?>
						<div class="left-arrow">
							<?php slide_arrow(); ?>
						</div>
						<div class="right-arrow">
							<?php slide_arrow(); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
		<?php
	}
}

function objectiv_companies_cosulted_with() {
	$section_title = get_field( 'cons_section_title' );
	$companies     = get_field( 'cons_companies_consulted' );
	?>
	<?php if ( ! empty( $companies ) ) : ?>
		<section class="page-section companies-consulted-with spt">
			<div class="wrap">
				<?php if ( ! empty( $section_title ) ) : ?>
					<h2 class="section-title companies-consulted-with-section-title tac fwb f32 border0"><?php echo $section_title; ?></h2>
				<?php endif; ?>
				<div class="companies-consulted-logos-wrap">
					<?php
					foreach ( $companies as $company ) :
						$logo        = $company['logo'];
						$logo_url    = $logo['url'];
						$alt         = $logo['alt'];
						$company_url = $company['company_url'];
						?>
					<div class="logo">
						<?php if ( ! empty( $company_url ) ) : ?>
							<a class="logo-wrap" href="<?php echo $company_url; ?>">
								<img src="<?php echo $logo_url; ?>" alt="<?php echo $alt; ?>">
							</a>
						<?php else : ?>
							<div class="logo-wrap">
								<img src="<?php echo $logo_url; ?>" alt="<?php echo $alt; ?>">
							</div>
						<?php endif; ?>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	endif;
}

function objectiv_companies_advisor_role() {
	$section_title = get_field( 'cons_advising_section_title' );
	$current_title = get_field( 'cons_currently_advising_title' );
	$current_ad    = get_field( 'cons_currently_advising' );
	$former_title  = get_field( 'cons_formerly_advised_title' );
	$former_ad     = get_field( 'cons_former_advising' );
	?>

	<?php if ( ! empty( $current_ad ) || ! empty( $former_ad ) ) : ?>
		<section class="page-section advisor-companies-section bg-light-gray spt spb">
			<div class="wrap">
				<?php if ( ! empty( $section_title ) ) : ?>
					<h2 class="section-title advisor-companies-section-title tac fwb f32 border0 mb2"><?php echo $section_title; ?></h2>
				<?php endif; ?>
				<div class="advisor-companies-blocks-wrap one2grid">
					<?php if ( ! empty( $current_ad ) ) : ?>
						<div class="advisor-companies-block tac">
							<?php if ( ! empty( $current_title ) ) : ?>
								<h4 class="f24"><?php echo $current_title; ?></h4>
							<?php endif; ?>
							<div class="logos-wrap">
								<?php
								foreach ( $current_ad as $ca ) :
									$logo        = $ca['logo'];
									$logo_url    = $logo['url'];
									$alt         = $logo['alt'];
									$company_url = $ca['company_url'];
									$sub_text    = $ca['sub_text'];
									?>
								<div class="logo">
									<?php if ( ! empty( $company_url ) ) : ?>
										<a class="logo-wrap" href="<?php echo $company_url; ?>">
											<img src="<?php echo $logo_url; ?>" alt="<?php echo $alt; ?>">
										</a>
									<?php else : ?>
										<div class="logo-wrap">
											<img src="<?php echo $logo_url; ?>" alt="<?php echo $alt; ?>">
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $sub_text ) ) : ?>
										<div class="logo-sub-text f14"><?php echo $sub_text; ?></div>
									<?php endif; ?>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if ( ! empty( $former_ad ) ) : ?>
						<div class="advisor-companies-block tac">
							<?php if ( ! empty( $former_title ) ) : ?>
								<h4 class="f24"><?php echo $former_title; ?></h4>
							<?php endif; ?>
							<div class="logos-wrap">
								<?php
								foreach ( $former_ad as $ca ) :
									$logo        = $ca['logo'];
									$logo_url    = $logo['url'];
									$alt         = $logo['alt'];
									$company_url = $ca['company_url'];
									$sub_text    = $ca['sub_text'];
									?>
								<div class="logo">
									<?php if ( ! empty( $company_url ) ) : ?>
										<a class="logo-wrap" href="<?php echo $company_url; ?>">
											<img src="<?php echo $logo_url; ?>" alt="<?php echo $alt; ?>">
										</a>
									<?php else : ?>
										<div class="logo-wrap">
											<img src="<?php echo $logo_url; ?>" alt="<?php echo $alt; ?>">
										</div>
									<?php endif; ?>
									<?php if ( ! empty( $sub_text ) ) : ?>
										<div class="logo-sub-text f14"><?php echo $sub_text; ?></div>
									<?php endif; ?>
								</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>
	<?php
}

function objectiv_consulting_content_form() {
	$section_title = get_field( 'cont_form_section_title' );
	$intro_content = get_field( 'cont_form_intro_content' );
	$form          = get_field( 'cont_form_form' );

	objectiv_do_gravity_form_section( $section_title, $intro_content, '', $form, 'form-section', 'bg-light-gray' );
}

genesis();
