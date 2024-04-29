<?php

// Helpers for the conference listings

function get_single_conference_details( $id ) {
	$conference = array();
	$conf_page  = mk_get_field( 'conferences_page', 'option' );

	$conference['id']                  = $id;
	$conference['title']               = get_the_title( $id );
	$conference['permalink']           = get_the_permalink( $id );
	$conference['conf_list_permalink'] = get_the_permalink( $conf_page );
	$conference['link']                = mk_get_field( 'link', $id );
	$conference['desc']                = mk_get_field( 'description', $id );
	$conference['type']                = get_conference_type_details( $id );
	$conference['date_details']        = get_conference_date_details( $id );
	$conference['price_details']       = get_conference_price_details( $id );
	$conference['early_bird_details']  = get_conference_early_bird_price_details( $id );
	$conference['location']            = get_conference_location_details( $id );
	$conference['badge_details']       = get_conference_badge_details( $id );
	$conference['speaker']             = get_conference_kitces_speaker_details( $id );
	$conference['focus']               = get_single_conference_terms( $id, 'focus' );
	$conference['organization']        = get_single_conference_terms( $id, 'organization' );
	$conference['industry-channel']    = get_single_conference_terms( $id, 'industry-channel' );

	return $conference;
}

function get_conference_select_taxonomy( $taxonomy = null, $all_string = 'All' ) {
	if ( empty( $taxonomy ) ) {
		return null;
	}

	$args = array(
		'orderby'    => 'name',
		'hide_empty' => true,
	);

	$terms        = get_terms( $taxonomy, $args );
	$terms_select = null;

	if ( is_array( $terms ) && ! empty( $terms ) ) {
		$terms_select .= '<select class="conf-' . $taxonomy . '-select">';
		$terms_select .= '<option value="all">' . $all_string . '</option>';

		foreach ( $terms as $t ) {
			$slug = $t->slug;
			$name = $t->name;

			$terms_select .= '<option value="' . $taxonomy . '-' . $slug . '">' . $name . '</option>';
		}

		$terms_select .= '</select>';
	}

	return $terms_select;
}

function get_conference_type_select( $conferences ) {

	$select_options = array();

	foreach ( $conferences as $conf ) {
		$type_details = get_conference_type_details( $conf->ID );

		if ( ! empty( $type_details ) && is_array( $type_details ) ) {
			$label                    = mk_key_value( $type_details, 'label' );
			$value                    = mk_key_value( $type_details, 'value' );
			$select_options[ $value ] = $label;
		}
	}

	$type_select = null;

	if ( is_array( $select_options ) && ! empty( $select_options ) ) {
		$type_select .= '<select class="conf-type-select">';
		$type_select .= '<option value="all">Type</option>';
		foreach ( $select_options as $slug => $name ) {
			$type_select .= '<option value="type-' . $slug . '">' . $name . '</option>';
		}
		$type_select .= '</select>';
	}

	return $type_select;

}

// Get the conference organizations states
function get_conference_fifty_states_select() {
	$states        = get_fifty_states();
	$states_select = null;

	if ( is_array( $states ) && ! empty( $states ) ) {
		$states_select .= '<select class="conf-states-select">';
		$states_select .= '<option value="all">State</option>';
		foreach ( $states as $slug => $name ) {
			$states_select .= '<option value="' . $slug . '">' . $name . '</option>';
		}
		$states_select .= '</select>';
	}

	return $states_select;

}

// Get the conference organizations
function get_fifty_states() {
	return array(
		'AL' => 'Alabama',
		'AK' => 'Alaska',
		'AZ' => 'Arizona',
		'AR' => 'Arkansas',
		'CA' => 'California',
		'CO' => 'Colorado',
		'CT' => 'Connecticut',
		'DE' => 'Delaware',
		'DC' => 'District of Columbia',
		'FL' => 'Florida',
		'GA' => 'Georgia',
		'HI' => 'Hawaii',
		'ID' => 'Idaho',
		'IL' => 'Illinois',
		'IN' => 'Indiana',
		'IA' => 'Iowa',
		'KS' => 'Kansas',
		'KY' => 'Kentucky',
		'LA' => 'Louisiana',
		'ME' => 'Maine',
		'MD' => 'Maryland',
		'MA' => 'Massachusetts',
		'MI' => 'Michigan',
		'MN' => 'Minnesota',
		'MS' => 'Mississippi',
		'MO' => 'Missouri',
		'MT' => 'Montana',
		'NE' => 'Nebraska',
		'NV' => 'Nevada',
		'NH' => 'New Hampshire',
		'NJ' => 'New Jersey',
		'NM' => 'New Mexico',
		'NY' => 'New York',
		'NC' => 'North Carolina',
		'ND' => 'North Dakota',
		'OH' => 'Ohio',
		'OK' => 'Oklahoma',
		'OR' => 'Oregon',
		'PA' => 'Pennsylvania',
		'RI' => 'Rhode Island',
		'SC' => 'South Carolina',
		'SD' => 'South Dakota',
		'TN' => 'Tennessee',
		'TX' => 'Texas',
		'UT' => 'Utah',
		'VT' => 'Vermont',
		'VA' => 'Virginia',
		'WA' => 'Washington',
		'WV' => 'West Virginia',
		'WI' => 'Wisconsin',
		'WY' => 'Wyoming',
	);
}
function get_single_conference_terms( $c_id, $taxonomy ) {
	$terms         = get_the_terms( $c_id, $taxonomy );
	$terms_slugs   = array();
	$terms_names   = array();
	$terms_classes = null;
	if (is_array($terms))
	{
		foreach ( $terms as $t ) {
			array_push( $terms_slugs, $t->slug );
			array_push( $terms_names, $t->name );
			$terms_classes .= $taxonomy . '-' . $t->slug . ' ';
		}
	}	

	return array(
		'term-slugs'      => $terms_slugs,
		'term-names'      => $terms_names,
		'term-names-list' => implode( ', ', $terms_names ),
		'term-data-list'  => $terms_classes,
	);
}


function get_conference_date_details( $id ) {
	$start_date = mk_get_field( 'conference_start_date', $id );
	$end_date   = mk_get_field( 'conference_end_date', $id );

	// Give us strtotime to work with
	$start_date_string = strtotime( $start_date );
	$end_date_string   = strtotime( $end_date );

	// Variables for Strings
	$month          = date_i18n( 'M', $start_date_string );
	$end_date_month = date_i18n( 'M', $end_date_string );
	$month_full     = date_i18n( 'F', $start_date_string );
	$start_day      = date_i18n( 'j', $start_date_string );
	$end_day        = date_i18n( 'j', $end_date_string );

	// Set up array
	$date_details['start-date']      = $start_date;
	$date_details['end-date']        = $end_date;
	$date_details['month']           = $month;
	$date_details['end-month']       = $end_date_month;
	$date_details['start-day']       = $start_day;
	$date_details['start-date-text'] = null;
	$date_details['end-date-text']   = null;

	if ( $start_date_string === $end_date_string ) {
		$end_date_string = null;
	}

	if ( ! empty( $end_date_string ) ) {
		if ( $month === $end_date_month ) {
			$date_details['end-day']   = $end_day;
			$date_details['days']      = $start_day . '-' . $end_day;
			$date_details['full-text'] = $month_full . ' ' . $start_day . '-' . $end_day;

		} else {
			$date_details['end-day']   = $end_day;
			$date_details['days']      = $start_day . '-' . $end_day;
			$date_details['full-text'] = $month . ' ' . $start_day . '-' . $end_date_month . ' ' . $end_day;
		}
		$date_details['start-date-text'] = $month . ' ' . $start_day;
		$date_details['end-date-text']   = $end_date_month . ' ' . $end_day;
	} else {
		$date_details['end-day']         = $start_day;
		$date_details['days']            = $start_day;
		$date_details['full-text']       = $month_full . ' ' . $start_day;
		$date_details['start-date-text'] = $month_full . ' ' . $start_day; // same month and same day single day event
		$date_details['end-date-text']   = $month_full . ' ' . $start_day; // same month and same day single day event
	}

	return $date_details;
}

function get_conference_price_details( $id ) {

	// Initial Details
	$high       = mk_get_field( 'price_high', $id );
	$low        = mk_get_field( 'price_low', $id );
	$high_int   = (int) $high;
	$low_int    = (int) $low;
	$high_final = number_format( $high_int );
	$low_final  = number_format( $low_int );

	// Set Up Array
	$price_details['price_high']       = $high_final;
	$price_details['price_low']        = $low_final;
	$price_details['price_high_money'] = '$' . $high_final;
	$price_details['price_low_money']  = '$' . $low_final;

	if ( $high_final === $low_final && 0 !== $low_int ) {
		$price_details['price_span'] = '$' . $high_final;
	} elseif ( ! empty( $low_final ) && 0 !== $low_int ) {
		$price_details['price_span'] = '$' . $low_final . '-' . '$' . $high_final;
	} elseif ( 0 === $low_int ) {
		$price_details['price_span'] = '$0-' . '$' . $high_final;
	} else {
		$price_details['price_span'] = '$' . $high_final;
	}

	if ( empty( $low_final ) && empty( $high_final ) ) {
		$price_details['price_span'] = 'TBD';
	}

	return $price_details;
}

function get_conference_early_bird_price_details( $id ) {
	// Initial Details
	$high        = mk_get_field( 'early_price_high', $id );
	$low         = mk_get_field( 'early_price_low', $id );
	$expire_date = mk_get_field( 'early_bird_expiration', $id );
	$high        = (int) $high;
	$low         = (int) $low;

	if ( $high === $low ) {
		$low = null;
	}

	// Decide Whether Should Display or Not
	$now_date_string                       = strtotime( 'now' );
	$expire_date_string                    = strtotime( $expire_date );
	$price_details['expiration']           = $expire_date_string;
	$price_details['expiration_formatted'] = date_i18n( 'm/j/y', $expire_date_string );

	if ( $now_date_string > $expire_date_string ) {
		$price_details['unexpired'] = false;
	} else {
		$price_details['unexpired'] = true;
	}

	// Set Up Array
	$price_details['price_high']       = $high;
	$price_details['price_low']        = $low;
	$price_details['price_high_money'] = '$' . $high;
	$price_details['price_low_money']  = '$' . $low;

	if ( ! empty( $low ) ) {
		$price_details['price_span'] = '$' . $low . '-' . '$' . $high;
	} else {
		$price_details['price_span'] = '$' . $high;
	}

	return $price_details;
}

function get_conference_location_details( $id ) {
	$city       = mk_get_field( 'city', $id );
	$state      = mk_get_field( 'state', $id );
	$city_state = $city . ', ' . $state;

	if ( empty( $state ) ) {
		if ( empty( $city ) ) {
			$city_state = 'Location TBD';
		} else {
			$city_state = $city;
		}
	}

	return array(
		'city'        => $city,
		'state'       => $state,
		'city_state'  => $city_state,
		'state_class' => 'state-' . $state,
	);
}

function get_conference_type_details( $id ) {
	$type  = mk_get_field( 'conference_type', $id, true, true );
	$label = null;
	$value = null;

	if ( is_array( $type ) && ! empty( $type ) ) {
		$label = mk_key_value( $type, 'label' );
		$value = mk_key_value( $type, 'value' );

		return array(
			'value'      => $value,
			'label'      => $label,
			'type_class' => 'type-' . $value,
		);
	}

	return null;

}

function get_conference_badge_details( $id ) {

	// Top Conference Badge
	$top_conf            = mk_get_field( 'kitces_top_conference', $id );
	$top_conf_image_id   = null;
	$top_conf_class      = null;
	$top_conf_image_link = null;

	if ( $top_conf ) {
		$top_conf_image_id   = mk_get_field( 'top_conference_badge', 'option' );
		$top_conf_image_link = mk_get_field( 'top_conference_badge_link', 'option' );
		$top_conf_class      = 'top-conf';
	}

	// New Worth Watching Badge
	$nww_conf            = mk_get_field( 'kitces_new_worth_watching', $id );
	$nww_conf_image_id   = null;
	$nww_conf_class      = null;
	$nww_conf_image_link = null;

	if ( $nww_conf ) {
		$nww_conf_image_id   = mk_get_field( 'new_and_worth_watching_badge', 'option' );
		$nww_conf_image_link = mk_get_field( 'new_and_worth_watching_badge_link', 'option' );
		$nww_conf_class      = 'nww-conf';
	}

	return array(
		'top-conference'            => $top_conf,
		'top-conference-class'      => $top_conf_class,
		'top-conference-badge'      => wp_get_attachment_image_url( $top_conf_image_id, 'medium', false ),
		'top-conference-badge-link' => $top_conf_image_link,
		'nww-conference'            => $nww_conf,
		'nww-conference-class'      => $nww_conf_class,
		'nww-conference-badge'      => wp_get_attachment_image_url( $nww_conf_image_id, 'medium', false ),
		'nww-conference-badge-link' => $nww_conf_image_link,
	);
}

function get_conference_kitces_speaker_details( $id ) {
	$kitces_speaker_event   = mk_get_field( 'kitces_speaker_event', $id );
	$speaker                = null;
	$event_topic            = null;
	$event_speaking_time    = null;
	$speaker_image          = null;
	$speaker_class          = null;
	$speaker_name           = null;
	$speaker_slug           = null;
	$speaker_filter_classes = null;
	$additional_speakers    = null;

	if ( ! empty( $kitces_speaker_event ) ) {
		$speaker                 = obj_get_event_speaker( $kitces_speaker_event );
		$event_topic             = obj_get_event_topics( $kitces_speaker_event );
		$event_speaking_time     = obj_get_speaking_time( $kitces_speaker_event );
		$additional_speakers_raw = obj_get_event_additional_speakers( $kitces_speaker_event );

		if ( is_array( $speaker ) ) {
			$speaker_class          = obj_get_speaker_class( null, $speaker );
			$speaker_name           = obj_get_speaker_name( null, $speaker );
			$speaker_slug           = obj_get_speaker_slug( null, $speaker );
			$speaker_image          = obj_get_speaker_image( $speaker_slug );
			$speaker_filter_classes = obj_get_event_speakers_class_string( $speaker, $additional_speakers_raw );

			if ( is_array( $additional_speakers_raw ) ) {
				$count = 0;
				foreach ( $additional_speakers_raw as $as ) {
					$as_speaker       = mk_key_value( $as, 'speaker' );
					$as_topics        = mk_key_value( $as, 'topics_summary' );
					$as_speaker_class = obj_get_speaker_class( null, $as_speaker );
					$as_speaker_name  = obj_get_speaker_name( null, $as_speaker );
					$as_speaker_slug  = obj_get_speaker_slug( null, $as_speaker );
					$as_speaker_image = obj_get_speaker_image( $as_speaker_slug );

					$additional_speakers[ 'additional_speaker_' . $count ] = array(
						'speaker'                => $as_speaker,
						'speaker_class'          => $as_speaker_class,
						'speaker_name'           => $as_speaker_name,
						'speaker_talk_topic'     => $as_topics,
						'speaker_image'          => $as_speaker_image,
						'speaker_filter_classes' => $as_speaker_class,
					);

					$count++;
				}
			}
		}
	}

	return array(
		'event_id'               => $kitces_speaker_event,
		'speaker'                => $speaker,
		'speaker_class'          => $speaker_class,
		'speaker_name'           => $speaker_name,
		'speaker_talk_topic'     => $event_topic,
		'speaker_talk_time'      => $event_speaking_time,
		'speaker_image'          => $speaker_image,
		'speaker_filter_classes' => $speaker_filter_classes,
		'additional_speakers'    => $additional_speakers,
	);
}

function conferences_in_full_month_array( $conferences = null ) {
	$conf_by_month = array();

	foreach ( $conferences as $c ) {
		$id              = $c->ID;
		$start_date      = mk_get_field( 'conference_start_date', $id );
		$start_date_to_s = strtotime( $start_date );
		$month_key       = date_i18n( 'Y-m', $start_date_to_s );

		$conference = get_single_conference_details( $id );

		$conf_by_month[ $month_key ][ $start_date_to_s . $id ] = $conference;
	}

	$start_month = strtotime( key( $conf_by_month ) );
	end( $conf_by_month );
	$last_month = strtotime( key( $conf_by_month ) );
	reset( $conf_by_month );

	while ( $start_month < $last_month ) {
		$start_month = strtotime( '+1 month', $start_month );
		$month_key   = date_i18n( 'Y-m', $start_month );

		if ( ! array_key_exists( $month_key, $conf_by_month ) ) {
			$conf_by_month[ $month_key ] = null;
		}
	}

	ksort( $conf_by_month );

	return $conf_by_month;
}

function do_conference_list( $conferences ) {
	if ( empty( $conferences || ! is_array( $conferences ) ) ) {
		return;
	}

	$conf_by_month = conferences_in_full_month_array( $conferences );

	echo "<div class='conference-list-wrap'>";
	foreach ( $conf_by_month as $key => $month_confs ) {
		$month_name = date_i18n( 'F Y', strtotime( $key ) );
		if ( ! empty( $month_name ) && ! empty( $month_confs ) ) {
			echo "<div class='conference-list-month'>";
			echo "<h3 class='f24'>$month_name</h3>";
			echo "<div class='month-error hidden'>No conferences matching filter.</div>";
			foreach ( $month_confs as $conf ) {
				do_conference_block( $conf );
			}
			echo '</div>';
		} else {
			echo "<div class='conference-list-month'>";
			echo "<h3 class='f24'>$month_name</h3>";
			echo "<div class='no-conferences-error'>No conferences for this month.</div>";
			echo "<div class='month-error hidden'>No conferences matching filter.</div>";
			echo '</div>';
		}
	}
	echo '</div>';
}

function do_conference_block( $conf = null, $single = false ) {
	if ( ! empty( $conf ) ) {
		$single_class = ( $single ? 'single-conf-block' : '' );
		?>
		<div
			class='conference-block active
			<?php echo $conf['focus']['term-data-list']; ?>
			<?php echo $conf['organization']['term-data-list']; ?>
			<?php echo $conf['industry-channel']['term-data-list']; ?>
			<?php echo $conf['location']['state_class']; ?>
			<?php echo $conf['speaker']['speaker_filter_classes']; ?>
			<?php echo $conf['badge_details']['top-conference-class']; ?>
			<?php echo $conf['badge_details']['nww-conference-class']; ?>
			<?php echo $conf['type']['type_class']; ?>
			<?php echo $single_class; ?>
			'
		>
			<?php if ( ! $single ) : ?>
				<div class="conf-meta">
					<div class="date-loc">
						<?php if ( $conf['date_details']['month'] !== $conf['date_details']['end-month'] ) : ?>
							<div class="span-months">
								<?php echo $conf['date_details']['start-date-text']; ?>
								-
								<?php echo $conf['date_details']['end-date-text']; ?>
							</div>
						<?php else : ?>
							<div class="month"><?php echo $conf['date_details']['month']; ?></div>
							<div class="days"><?php echo $conf['date_details']['days']; ?></div>
						<?php endif; ?>
						<div class="location"><?php echo $conf['location']['city_state']; ?></div>
					</div>
					<?php do_conf_badges( $conf, true ); ?>
				</div>
			<?php endif; ?>
			<div class="conf-details">
				<div class="conf-header">
					<?php if ( $single ) : ?>
						<h1 class="title f36"><?php echo $conf['title']; ?></h1>
					<?php else : ?>
						<a class="no-underline" href="<?php echo $conf['permalink']; ?>">
							<h3 class="title f24"><?php echo $conf['title']; ?></h3>
						</a>
					<?php endif; ?>
					<div class="conf-meta-below-title">
						<?php if ( $single ) : ?>
							<div class="conference-date italic"><?php echo $conf['date_details']['full-text']; ?><span class="separator">&nbsp;|&nbsp;</span></div>
							<div class="conference-location italic"><?php echo $conf['location']['city_state']; ?><span class="separator">&nbsp;|&nbsp;</span></div>
						<?php endif; ?>
						<?php if ( ! empty( $conf['link'] ) ) : ?>
							<?php
								$link_text = mk_key_value( $conf['link'], 'title' );

							if ( ! $link_text ) {
								$link_text = mk_key_value( $conf['link'], 'url' );
							}
							?>
							<div class="external-link-wrap">
								<a class="external-link" target="_blank" href="<?php echo $conf['link']['url']; ?>"><?php echo $link_text; ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="desc-wrap">
					<div class="left last-child-margin-bottom-0"><?php echo wpautop( $conf['desc'] ); ?></div>
					<?php if ( ! $single ) : ?>
						<div class="right">
							<?php do_conference_side_details( $conf ); ?>
						</div>
					<?php endif; ?>
				</div>
				<?php if ( ! $single ) : ?>
					<?php do_conference_speaker_details( $conf ); ?>
				<?php endif; ?>
			</div>
			<?php if ( $single ) : ?>
				<div class="single-side-details">
					<?php do_conf_badges( $conf, false ); ?>
					<?php do_conference_side_details( $conf ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php if ( $single ) : ?>
			<?php do_conference_speaker_details( $conf ); ?>
			<a class="mt1 block" href="<?php echo $conf['conf_list_permalink']; ?>">Back to All Conferences</a>
		<?php endif; ?>
		<?php
	}
}

function do_conference_speaker_details( $conf ) {
	$main_speaker        = ! empty( $conf['speaker']['speaker_name'] );
	$additional_speakers = ! empty( $conf['speaker']['additional_speakers'] ) && is_array( $conf['speaker']['additional_speakers'] );
	?>
	<?php if ( $main_speaker || $additional_speakers ) : ?>
		<div class="kitces-speaker-wrap">
			<?php if ( $main_speaker ) : ?>
				<?php output_conference_speaker_details( $conf['speaker'] ); ?>
			<?php endif; ?>
			<?php if ( $additional_speakers ) : ?>
				<?php foreach ( $conf['speaker']['additional_speakers'] as $add_speaker ) : ?>
					<?php output_conference_speaker_details( $add_speaker ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php
}

function output_conference_speaker_details( $speaker_details = null ) {
	?>
	<?php if ( ! empty( $speaker_details['speaker_name'] ) ) : ?>
		<div class="kitces-speaker <?php echo $speaker_details['speaker_class']; ?>">
			<div class="image-wrap">
				<?php if ( ! empty( $speaker_details['speaker_image'] ) ) : ?>
					<img src="<?php echo $speaker_details['speaker_image']; ?>" alt="<?php echo $speaker_details['speaker_name']; ?>">
				<?php endif; ?>
			</div>
			<div class="name-deets">
				<div class="name-title">Speaker:</div>
				<div class="name"><?php echo $speaker_details['speaker_name']; ?></div>
				<div class="deets"><?php echo $speaker_details['speaker_talk_time']; ?></div>
			</div>
			<div class="talk-deets">
				<div class="topic-title">Topic:</div>
				<div class="talk-title"><?php echo wpautop( $speaker_details['speaker_talk_topic'] ); ?></div>
			</div>
		</div>
	<?php endif; ?>
	<?php
}

function do_conference_side_details( $conf ) {
	?>
	<?php if ( ! empty( $conf['price_details'] ) ) : ?>
		<div class="price detail">
			<span class="label">Price: </span><?php echo $conf['price_details']['price_span']; ?>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $conf['type'] ) ) : ?>
		<div class="type detail">
			<span class="label">Type: </span><?php echo $conf['type']['label']; ?>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $conf['early_bird_details'] ) && $conf['early_bird_details']['unexpired'] ) : ?>
		<div class="early-bird detail">
			<span class="label">Early Bird: </span><?php echo $conf['early_bird_details']['price_span']; ?>
			<?php if ( ! empty( $conf['early_bird_details']['expiration_formatted'] ) ) : ?>
				until <?php echo $conf['early_bird_details']['expiration_formatted']; ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $conf['focus']['term-names-list'] ) ) : ?>
		<div class="focus detail">
			<span class="label">Content Focus: </span><?php echo $conf['focus']['term-names-list']; ?>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $conf['industry-channel']['term-names-list'] ) ) : ?>
		<div class="channel detail">
			<span class="label">Channel: </span><?php echo $conf['industry-channel']['term-names-list']; ?>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $conf['organization']['term-names-list'] ) ) : ?>
		<div class="organizer detail">
			<span class="label">Organizer: </span><?php echo $conf['organization']['term-names-list']; ?>
		</div>
	<?php endif; ?>
	<?php
}

function do_conf_badges( $conf = null, $link = true ) {
	if ( ! empty( $conf ) && is_array( $conf ) && array_key_exists( 'badge_details', $conf ) ) {
		?>
			<?php if ( $conf['badge_details']['top-conference'] ) : ?>
				<div class="conf-badge">
					<?php if ( $link && ! empty( $conf['badge_details']['top-conference-badge-link'] ) ) : ?>
						<a href="<?php echo $conf['badge_details']['top-conference-badge-link']['url']; ?>">
							<img src="<?php echo $conf['badge_details']['top-conference-badge']; ?>" alt="Kitces Top Conference Badge">
						</a>
					<?php else : ?>
						<img src="<?php echo $conf['badge_details']['top-conference-badge']; ?>" alt="Kitces Top Conference Badge">
					<?php endif; ?>
				</div>
			<?php endif; ?>
			<?php if ( $conf['badge_details']['nww-conference'] ) : ?>
				<div class="conf-badge">
					<?php if ( $link && ! empty( $conf['badge_details']['nww-conference-badge-link'] ) ) : ?>
						<a href="<?php echo $conf['badge_details']['nww-conference-badge-link']['url']; ?>">
							<img src="<?php echo $conf['badge_details']['nww-conference-badge']; ?>" alt="Kitces New and Worth Watching Conference Badge">
						</a>
					<?php else : ?>
						<img src="<?php echo $conf['badge_details']['nww-conference-badge']; ?>" alt="Kitces New and Worth Watching Conference Badge">
					<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php
	}
}

function conf_year() {
	$start_date      = mk_get_field( 'conference_start_date', get_the_ID() );
	$start_date_to_s = strtotime( $start_date );
	$year            = date_i18n( 'Y', $start_date_to_s );

	if ( ! empty( $year ) ) {
		return $year;
	} else {
		return null;
	}
}
add_shortcode( 'conf_year', 'conf_year' );
