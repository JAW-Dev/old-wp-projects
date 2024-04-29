<?php

/*
Template Name: Conferences List
*/
add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {
	$classes[] = 'conferences-list';
	return $classes;
}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );

// Add the Page Sections
add_action( 'genesis_after_header', 'objectiv_conferences_all_content' );

// Function if user checks the 'Show all Conferences' Checkbox
// If switch is ON the date is set to past date and user will be able to see
// all conferences (expired or not).
// If switch is OFF the date is set to now (Ymd).
function kitces_switch_on_off() {
	$dateConference = date( 'Ymd' );
	if ( isset( $_GET['kitces_show_all_conferences'] ) ) {
		// Checkbox was checked. Show all conferences
		$dateConference = date( 'Y-m-d', strtotime( '2017-02-02 17:02:03' ) );
	} else {
		// Only show future conferences
		$dateConference = date( 'Ymd' );
	}
	return $dateConference;
}

// Function gets the return from kitces_switch_on_off ().
// If checkbox OFF it returns ('Ymd') and it shows only conferences starting from today.
// If checkbox ON it returns (date("Y-m-d", strtotime('2017-02-02 17:02:03'));) and it shows all conferences (including experied).
function get_future_conferences() {
	$args        = array(
		'numberposts' => -1,
		'post_type'   => 'conference',
		'post_status' => 'publish',
		'meta_key'    => 'conference_start_date',
		'orderby'     => 'meta_value',
		'order'       => 'ASC',
		'meta_query'  => array(
			'key'     => 'conference_start_date',
			'value'   => kitces_switch_on_off(),
			'compare' => '>=',
		),
	);
	$conferences = get_posts( $args );
	return $conferences;
}

function objectiv_conferences_all_content() {
	$conferences = get_future_conferences();
	objectiv_intro_header();
	objectiv_first_content_sec();
	objectiv_filter_conferences_sec( $conferences );
	objectiv_conferences_list( $conferences );
}

function objectiv_intro_header() { ?>
	<?php
	$title     = mk_get_field( 'page_title_override' );
	$sub_title = mk_get_field( 'page_sub_title' );
	?>
	<section class="page-section spt spb bg-light-blue">
		<div class="wrap">
			<div class="head-content tac mw-800 mlra">
				<?php if ( ! empty( $title ) ) : ?>
					<h1 class="head-title wt fwb f48"><?php echo $title; ?></h1>
				<?php endif; ?>
				<?php if ( ! empty( $sub_title ) ) : ?>
					<p class="head-sub-title wt fwm f26"><?php echo $sub_title; ?></p>
				<?php endif; ?>
			</div>
		</div>
	</section>
	<?php
}

function objectiv_first_content_sec() {
	?>
	<?php
	$content = mk_get_field( 'first_content_content' );
	?>
	<?php if ( ! empty( $content ) ) : ?>
	<section class="page-section spt spb">
		<div class="wrap ml0 mw-970 mlra">
			<?php echo wpautop( $content ); ?>
		</div>
	</section>
	<?php endif; ?>
	<?php
}

function objectiv_filter_conferences_sec( $conferences = null ) {
	if ( ! empty( $conferences ) ) {
		$filter_title        = mk_get_field( 'filter_title' );
		$display_section     = mk_get_field( 'display_filter_section' );
		$focus_select        = get_conference_select_taxonomy( 'focus', 'Content Focus' );
		$industry_select     = get_conference_select_taxonomy( 'industry-channel', 'Industry Channel' );
		$org_select          = get_conference_select_taxonomy( 'organization', 'Organizer' );
		$fifty_states_select = get_conference_fifty_states_select();
		$type_select         = get_conference_type_select( $conferences );

		?>
		<?php if ( $display_section ) : ?>
			<section class="page-section spt spb bg-light-gray cfilter-section">
				<div class="wrap ml0 mw-970 mlra">
					<div class="cfilter-inner">
						<?php if ( ! empty( $filter_title ) ) : ?>
							<div class="f18 fwb tac"><?php echo $filter_title; ?></div>
						<?php endif; ?>
						<div class="filter-select-wraps">
							<?php if ( $focus_select ) : ?>
								<?php echo $focus_select; ?>
							<?php endif; ?>
							<?php if ( $industry_select ) : ?>
								<?php echo $industry_select; ?>
							<?php endif; ?>
							<?php if ( $org_select ) : ?>
								<?php echo $org_select; ?>
							<?php endif; ?>
							<?php if ( $fifty_states_select ) : ?>
								<?php echo $fifty_states_select; ?>
							<?php endif; ?>
							<?php if ( $type_select ) : ?>
								<?php echo $type_select; ?>
							<?php endif; ?>
							<select class="conf_kitces_speakers">
								<option value="all">All Speakers</option>
								<option value="speaker-mk">Michael Kitces</option>
								<option value="speaker-jeff">Jeffrey Levine</option>
								<option value="speaker-dt">Derek Tharp</option>
								<option value="speaker-ml">Meghaan Lurtz</option>
							</select>
							<div class="checkbox" style="margin-left:2%;">
								<input type="checkbox" id="top-conference" name="top-conference">
								<label for="top-conference">Kitces Top Conference</label>
							</div>
								<div class="show_all">
								<form method="GET" action="">
								<input type="checkbox" name="kitces_show_all_conferences" value="true" <?php echo isset( $_GET['kitces_show_all_conferences'] ) ? "checked='checked'" : ''; ?> onchange="this.form.submit()">
								<label for="kitces_show_all_conferences" >Show Past Conferences</label>
							</form>
						</div>

					</div>
					<div class="filter-reset">
								Reset Filters
							</div>
				</div>
			</section>
			<?php
		endif;
	}
}

function objectiv_conferences_list( $conferences = null ) {
	?>
		<?php if ( ! empty( $conferences ) ) : ?>
		<section class="page-section spt spb conference-list-section">
			<div class="wrap">
				<?php do_conference_list( $conferences ); ?>
			</div>
		</section>
		<?php endif; ?>
	<?php
}

genesis();
