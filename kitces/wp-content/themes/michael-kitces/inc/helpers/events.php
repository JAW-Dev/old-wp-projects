<?php

/**
 * Outputs Events by year along with a filter system for them
 *
 * Currently used on the speaking-schedule template
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_do_yearly_events( $years_to_display = null ) {
	if ( is_array( $years_to_display ) && ! empty( $years_to_display ) ) {
		echo "<div class='events-years__outer'>";
		foreach ( $years_to_display as $y ) {
			$intro_text = $y['intro_text'];
			$start_date = $y['event_list_start_date'];
			$end_date   = $y['event_list_end_date'];
			$year_id    = obj_id_from_string( $start_date, true, 10 );

			echo "<div class='events-years__year' id='{$year_id}'>";
			if ( ! empty( $intro_text ) ) {
				echo "<div class='events-years__intro-text'>";
				echo $intro_text;
				echo '</div>';
			}
			if ( ! empty( $start_date ) && ! empty( $year_id ) ) {
				$event_dates = obj_get_ot_events( $start_date, $end_date );
				obj_do_years_filters( $event_dates, $year_id );
				obj_do_years_events( $event_dates, $year_id );
			}
			echo '</div>';
		}
		echo '</div>';
	}
}

/**
 * Outputs a set of filters for the yearly schedule
 *
 * Cuurrently used on the speaking-schedule template
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */
function obj_do_years_filters( $event_dates = false, $year_id = null ) {
	$event_speakers = obj_get_speakers_array( $event_dates );
	$event_types    = obj_get_event_types_array( $event_dates );

	echo "<div class='events-year__filter' data-event-year='{$year_id}'>";
	?>
	<div class="filter-by-text">Filter By:</div>
	<div class="select-wrap">
		<select name="event-list__speakers" class="event-list__speakers-filter" id="event-list__speakers_<?php echo $year_id; ?>">
			<option value="all">All Speakers</option>
			<?php foreach ( $event_speakers as $abbr => $speaker ) : ?>
				<option value="<?php echo $abbr; ?>"><?php echo $speaker; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="filter-by-middle-text">and/or</div>
	<div class="select-wrap">
		<select name="event-list__type" class="event-list__type-filter" id="event-list__type_<?php echo $year_id; ?>">
			<option value="all">All Types</option>
			<?php foreach ( $event_types as $abbr => $type ) : ?>
				<option value="<?php echo $abbr; ?>"><?php echo $type; ?></option>
			<?php endforeach; ?>
		</select>
	</div>
	<div class="select-wrap">
		<input type="checkbox" class="event-list__future-filter" id="event-list__future-filter" name="event-list__future-filter" value="event-list__future-filter">
		<label for="event-list__future-filter">Only future events</label>
	</div>
	<div class="filter-reset">Reset</div>
	<?php
	echo '</div>';
}

/**
 * Outputs a set of filters for the yearly schedule
 *
 * Cuurrently used on the speaking-schedule template
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */
function obj_do_years_events( $event_dates = false, $year_id = null ) {
	$events_by_month = obj_arrange_events_by_month( $event_dates );

	echo "<div class='events-year__events' data-event-year='{$year_id}'>";
	obj_display_event_list_by_month( $events_by_month, $year_id );
	echo '</div>';
}

/**
 * Get a list of events
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_ot_events( $start_date = null, $end_date = null, $post_count = -1 ) {

	// Start and End Date to string
	if ( ! empty( $start_date ) ) {
		$start_date = strtotime( $start_date );
		$start_date = date( 'Ymd', $start_date );
	}

	if ( ! empty( $end_date ) ) {
		$end_date = strtotime( $end_date );
		$end_date = date( 'Ymd', $end_date );
	}

	// Set up Meta Query
	if ( ! empty( $start_date ) && empty( $end_date ) ) {
		$meta_query = array(
			array(
				'key'     => 'obj_event_start_date',
				'value'   => $start_date,
				'compare' => '>=',
			),
		);
	} elseif ( ! empty( $end_date ) && empty( $start_date ) ) {
		$meta_query = array(
			array(
				'key'     => 'obj_event_send_date',
				'value'   => $end_date,
				'compare' => '<=',
			),
		);
	} elseif ( ! empty( $end_date ) && ! empty( $start_date ) ) {
		$meta_query = array(
			'relation' => 'AND',
			array(
				'key'     => 'obj_event_start_date',
				'value'   => $start_date,
				'compare' => '>=',
			),
			array(
				'key'     => 'obj_event_send_date',
				'value'   => $end_date,
				'compare' => '<=',
			),
		);
	}

	$args = array(
		'numberposts' => $post_count,
		'post_type'   => 'event',
		'post_status' => 'publish',
		'meta_key'    => 'obj_event_start_date',
		'orderby'     => 'meta_value',
		'order'       => 'ASC',
		'meta_query'  => $meta_query,
	);

	$events = get_posts( $args );

	$count         = 0;
	$sorted_events = array();

	foreach ( $events as $e ) {
		$event_id         = $e->ID;
		$event_start_date = obj_get_event_start_date( $event_id );

		if ( ! empty( $event_start_date ) ) {
			$sorted_events[ $event_start_date + $count ][] = $event_id;

			// Count is here to avoid issues with multiple events on the same day.
			if ( $count >= 99 ) {
				$count = 0;
			} else {
				$count ++;
			}
		}
	}
	ksort( $sorted_events );

	return $sorted_events;
}

function obj_flat_events_array( $events = null ) {
	if ( ! empty( $events ) ) {
		$single_level_events = array();
		foreach ( $events as $event_array ) {
			foreach ( $event_array as $event_id ) {
				array_push( $single_level_events, $event_id );
			}
		}
		return $single_level_events;
	}
}


/**
 * Returns array of events listed by months
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_arrange_events_by_month( $event_dates = null ) {
	if ( ! empty( $event_dates ) ) {
		$events_by_month = array();

		foreach ( $event_dates as $date_key => $event_date ) {
			$month = date_i18n( 'Y-m', $date_key );
			foreach ( $event_date as $event ) {
				if ( ! empty( $month ) ) {
					$events_by_month[ $month ][ $date_key ] = $event;
				}
			}
		}

		return $events_by_month;
	}
}

/**
 * Returns array of speakers from array of event_dates
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */
function obj_get_speakers_array( $event_dates = null ) {
	if ( ! empty( $event_dates ) ) {
		$event_speakers = array();
		$speakers       = get_field( 'remain_persons', 'option' ); // Pull speakers from theme options

		if ( ! empty( $speakers ) && is_array( $speakers ) ) {
			foreach ( $speakers as $speaker ) {
				$full_name = mk_key_value( $speaker, 'name' );
				$slug      = mk_key_value( $speaker, 'slug' );

				if ( $slug ) {
					$event_speakers[ $slug ] = $full_name;
				}
			}
		}

		return $event_speakers;
	}
}

/**
 * Returns array of event types from array of event_dates
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */
function obj_get_event_types_array( $event_dates = null ) {
	if ( ! empty( $event_dates ) ) {
		$event_types = array();

		foreach ( $event_dates as $event_date ) {
			foreach ( $event_date as $event ) {
				$type = obj_get_event_type( $event );
				if ( ! empty( $type ) ) {
					if ( ! array_key_exists( $type['value'], $event_types ) ) {
						$event_types[ $type['value'] ] = $type['label'];
					}
				}
			}
		}

		return $event_types;
	}
}

/**
 * List of event months
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_display_event_list_by_month( $event_months = null, $year_id = null ) {
	if ( ! empty( $event_months ) && is_array( $event_months ) ) {
		$month_year   = null;
		$current_date = date( 'Y/m/d' );
        echo "<div class='events-no-results-for-filter tac'>Sorry, no events match your filter.</div>";
		echo "<div class='events-months' data-event-year='{$year_id}'>";
		foreach ( $event_months as $month => $event_dates ) {
			$month              = strtotime( $month );
			$month_name         = date_i18n( 'F Y', $month );
			$current_month_year = date_i18n( 'Y', $month );
			$display_new_year   = false;

			if ( empty( $month_year ) ) {
				$month_year = date_i18n( 'Y', $month );
			}

			if ( $month_year !== $current_month_year ) {
				$month_year       = $current_month_year;
				$display_new_year = true;
			}

			?>

			<?php if ( $display_new_year ) : ?>
				<h2 class="events-year__break-title"><?php echo $month_year; ?> Events</h2>
			<?php endif; ?>
			<div class="events-month">
					<h3 class="event-month__header-title"><?php echo $month_name; ?></h3>
				<header class="event-month__header">
					<div class="event-month__header-date">Date</div>
					<div class="event-month__header-speaker">Speaker</div>
					<div class="event-month__header-location">Location</div>
					<div class="event-month__header-details">Event / Details</div>
					<div class="event-month__header-topics">Topics</div>
				</header>
				<div class="event-month__events-list">
					<?php
					foreach ( $event_dates as $event_id ) :
						$speaker                = obj_get_event_speaker( $event_id );
						$speaker_time           = obj_get_speaking_time( $event_id );
						$type                   = obj_get_event_type( $event_id );
						$date_string            = obj_get_event_days_string( $event_id );
						$location               = obj_get_event_location( $event_id );
						$link                   = obj_get_event_link( $event_id );
						$topics                 = obj_get_event_topics( $event_id );
						$title                  = obj_get_event_title( $event_id );
						$start_time             = obj_get_event_start_date( $event_id );
						$today_time             = strtotime( $current_date );
						$additional_speakers    = obj_get_event_additional_speakers( $event_id );
						$speaker_filter_classes = obj_get_event_speakers_class_string( $speaker, $additional_speakers );
						$main_speaker_class     = obj_get_speaker_class( null, $speaker );

						if ( is_array( $type ) ) {
							$type_class = 'type-' . $type['value'];
						} else {
							$type_class = null;
						}

						if ( is_array( $speaker ) ) {
							$speaker_name = $speaker['label'];
						} else {
							$speaker_name = null;
						}

						if ( $start_time >= $today_time ) {
							$fp_event_class = 'future-event';
						} else {
							$fp_event_class = 'past-event';
						}

						?>
						<div class="event-list__event_wrapper <?php echo $speaker_filter_classes; ?> <?php echo $type_class; ?> <?php echo $fp_event_class; ?>">
							<!-- Main Speaker -->
							<div class="event-list__event <?php echo $main_speaker_class; ?>">
								<div class="event-list__event-date"><?php echo $date_string; ?></div>
								<div class="event-list__event-speaker"><?php echo $speaker_name; ?></div>
								<div class="event-list__event-location"><?php echo $location; ?></div>
								<?php if ( ! empty( $link ) && array_key_exists( 'url', $link ) && ! empty( $link['url'] ) ) : ?>
									<div class="event-list__event-details"><?php echo mk_link_html( $link ); ?></div>
								<?php else : ?>
									<div class="event-list__event-details"><?php echo $title; ?></div>
								<?php endif; ?>
								<div class="event-list__event-topics"><?php echo $topics; ?></div>
							</div>
							<?php if ( ! empty( $additional_speakers ) && is_array( $additional_speakers ) ) : ?>
								<?php foreach ( $additional_speakers as $additional_speaker ) : ?>
									<?php
										$speaker       = mk_key_value( $additional_speaker, 'speaker' );
										$topics        = mk_key_value( $additional_speaker, 'topics_summary' );
										$speaker_class = obj_get_speaker_class( null, $speaker );
										$speaker_name  = obj_get_speaker_name( null, $speaker );
									?>
									<div class="event-list__event <?php echo $speaker_class; ?>">
										<div class="event-list__event-date"></div>
										<div class="event-list__event-speaker"><?php echo $speaker_name; ?></div>
										<div class="event-list__event-location"><?php echo $location; ?></div>
										<?php if ( ! empty( $link ) && array_key_exists( 'url', $link ) && ! empty( $link['url'] ) ) : ?>
											<div class="event-list__event-details"><?php echo mk_link_html( $link ); ?></div>
										<?php else : ?>
											<div class="event-list__event-details"><?php echo $title; ?></div>
										<?php endif; ?>
										<div class="event-list__event-topics"><?php echo $topics; ?></div>
									</div>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
			<?php
		}
		echo '</div>';
	}
}

/**
 * Retrieve an Events Start Date
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_start_date( $event_id = null ) {
	$event_start_date = get_field( 'obj_event_start_date', $event_id );

	return strtotime( $event_start_date );
}

/**
 * Retrieve an Events End Date
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_end_date( $event_id = null ) {
	$event_end_date = get_field( 'obj_event_send_date', $event_id );

	return strtotime( $event_end_date );
}

/**
 * Retrieve an Events "Days String"
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_days_string( $event_id = null ) {
	$start_date  = obj_get_event_start_date( $event_id );
	$end_date    = obj_get_event_end_date( $event_id );
	$date_string = null;

	if ( ! empty( $start_date ) ) {
		$start_month = date_i18n( 'M', $start_date );
		$start_date  = date_i18n( 'j', $start_date );
	} else {
		$start_month = '';
		$start_date  = '';
	}

	if ( ! empty( $end_date ) ) {
		$end_month = date_i18n( 'M', $end_date );
		$end_date  = date_i18n( 'j', $end_date );
	} else {
		$end_month = '';
		$end_date  = '';
	}

	if ( ! empty( $start_date ) && ! empty( $end_date ) ) {
		if ( $start_month === $end_month ) {
			$date_string = $start_date . '-' . $end_date;
		} else {
			$date_string = $start_month . ' ' . $start_date . ' - ' . $end_month . ' ' . $end_date;
		}
	} elseif ( ! empty( $start_date ) ) {
		$date_string = $start_date;
	}

	return $date_string;
}

/**
 * Retrieve an Events Location
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_location( $event_id = null ) {
	$location = get_field( 'event_location', $event_id );
	$type     = get_field( 'type', $event_id );

	if ( empty( $location ) ) {
		$location = get_post_custom_values( 'ot_e_location', $event_id )[0];
	}

	if ( is_array( $type ) && $type['value'] === 'web' ) {
		$location = 'Webinar';
	}

	return $location;
}

function obj_get_event_title( $event_id = null ) {
	$private_event = get_field( 'event_private_event', $event_id );
	$title         = get_the_title( $event_id );

	if ( $private_event ) {
		$title = 'Private Event';
	}

	return $title;
}

/**
 * Retrieve an Events Link Out
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_link( $event_id = null ) {
	$link          = get_field( 'event_link', $event_id );
	$private_event = get_field( 'event_private_event', $event_id );
	$link_title    = obj_get_event_title( $event_id );

	if ( empty( $link ) ) {
		$link = get_post_meta( $event_id, 'ot_e_link', true );

		if ( ! empty( $link ) ) {
			$link = array(
				'title'  => $link_title,
				'url'    => $link,
				'target' => '_blank',
			);
		}
	} else {
		$link['title']  = $link_title;
		$link['target'] = '_blank';
	}

	// If its a private event lets make that null
	if ( $private_event && isset( $link['url'] ) ) {
		$link['url'] = null;
	}

	return $link;
}

/**
 * Retrieve an Events Topics
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_topics( $event_id = null ) {
	$topics = get_field( 'event_topics_summary', $event_id );

	if ( empty( $topics ) ) {
		$post   = get_post( $event_id );
		$topics = $post->post_content;
	}

	return $topics;
}

/**
 * Retrieve an Events Type
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_type( $event_id = null ) {
	$type = get_field( 'type', $event_id );

	if ( empty( $type ) ) {
		$location = get_post_custom_values( 'ot_e_location', $event_id )[0];
		if ( $location === 'Webinar' ) {
			$type = array(
				'value' => 'web',
				'label' => 'Webinar',
			);
		} else {
			$type = array(
				'value' => 'live',
				'label' => 'Live',
			);
		}
	}

	return $type;
}

/**
 * Retrieve an Events Speaker
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_get_event_speaker( $event_id = null ) {
	$speaker = get_field( 'speaker', $event_id );

	if ( empty( $speaker ) ) {
		$speaker = array(
			'value' => 'mk',
			'label' => 'Michael Kitces',
		);
	}

	return $speaker;
}

function obj_get_event_additional_speakers( $event_id = null ) {
	return get_field( 'additional_speakers', $event_id );
}

function obj_get_event_speakers_class_string( $speaker = null, $additional_speakers = null ) {
	$classes = null;

	if ( is_array( $speaker ) ) {
		$classes .= obj_get_speaker_class( null, $speaker );
	}

	if ( is_array( $additional_speakers ) ) {
		foreach ( $additional_speakers as $speaker_slot ) {
			$speaker = mk_key_value( $speaker_slot, 'speaker' );
			if ( $speaker ) {
				$classes .= ' ' . obj_get_speaker_class( null, $speaker );
			}
		}
	}

	return $classes;
}

function obj_get_speaker_class( $event_id = null, $speaker = null ) {
	$speaker_class = null;

	if ( empty( $speaker ) ) {
		$speaker = obj_get_event_speaker( $event_id );
	}

	if ( is_array( $speaker ) ) {
		$speaker_class = 'speaker-' . $speaker['value'];
	}

	return $speaker_class;
}

function obj_get_speaker_name( $event_id = null, $speaker = null ) {
	$speaker_name = null;

	if ( empty( $speaker ) ) {
		$speaker = obj_get_event_speaker( $event_id );
	}

	if ( is_array( $speaker ) ) {
		$speaker_name = $speaker['label'];
	}

	return $speaker_name;
}

function obj_get_speaker_slug( $event_id = null, $speaker = null ) {
	$speaker_slug = null;

	if ( empty( $speaker ) ) {
		$speaker = obj_get_event_speaker( $event_id );
	}

	if ( is_array( $speaker ) ) {
		$speaker_slug = $speaker['value'];
	}

	return $speaker_slug;
}

function obj_get_speaking_time( $event_id = null ) {
	return mk_get_field( 'speaking_time', $event_id );
}

function obj_get_speaker_image( $speaker_slug = null ) {
	$speaker_availability = mk_get_field( 'remain_persons', 'option', true, true );
	$continue             = true;
	$speaker_image        = null;

	foreach ( $speaker_availability as $sa ) {
		if ( $continue && $sa['slug'] === $speaker_slug ) {
			$speaker_image = $sa['photo']['sizes']['small-square'];
		}
	}

	return $speaker_image;
}
