<?php
/*
Plugin Name: Out:Think Events
Plugin URI: http://outthinkgroup.com/
Description: This plugin provides a simple interface to add events in an upcoming list.
Version: 1.0
Author: Joseph Hinson
Author URI: http://outthinkgroup.com

	Copyright 2011 - Out:think Group  (email : joseph@outthinkgroup.com)

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Shortcode for pages to use events
function ot_calendar_func($atts, $content = null) {
extract(shortcode_atts(array(
	"start" => ""
), $atts));
global $post;
$ot_events = get_posts('numberposts=-1&meta_key=ot_e_date&orderby=meta_value&order=ASC&post_type=event&post_status=publish');
$master_return = ''; // this is the variable that actually gets returned.
$master_return = '
<style type="text/css" media="screen">
	.ot_event_list th {
		font-size: 14px;
		line-height: 21px;
		padding-bottom: 2px;
		border-bottom: 1px solid #434343;
		text-transform: uppercase;
		font-weight: normal;
		text-align: left;
	}
	.ot_event_list td.e_date {
		width: 10%;
	}
	.ot_event_list td.e_location {
		width: 15%;
	}
	.ot_event_list td.e_details {
		width: 40%;
	}
	.ot_event_list td.e_venue {
		width: 20%;
	}
	.ot_event_list .e_details span.event_details {
		display: block;
		margin-top: 7px;
	}
	.ot_event_list td {
		border-bottom: 1px solid #434343;
		padding-bottom: 10px;
		padding-top: 10px;
		line-height: 1.3;
		font-size: 15px;
		padding-right: 10px;
		vertical-align: top;
	}

	.ot_event_list td.e_date {
		font-size: 24px;
		line-height: 24px;
		padding-left:10px
	}
	td.even {
		background: #f5f5f5;
	}
	.ot_event_list tr.month td {
		font-size: 24px;
		text-transform: uppercase;
		border-bottom: 0px;
		padding-top: 20px;
		font-weight:normal;
		padding-bottom: 14px;
		border-bottom: 1px solid #434343;
	}
	.event_link a {
		text-decoration: underline;
	}

</style>
<script>
jQuery(document).ready(function() {
	jQuery("tr.ot_e_data:even td").addClass("even");
});
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="ot_event_list">
  <tr>
	<th>Date</th>
	<th>Location</th>
	<th>Event</th>
	<th class="e_details">Details</th>
  </tr>';
	$var = array();
foreach($ot_events as $event) :
	$origDate = get_post_meta($event->ID, 'ot_e_date', true);
	if ($start) {
		if (strtotime($start) < $origDate) {
			$month = date('F', $origDate);
			$day = date('j', $origDate);
			$eventTime = $origDate;
			$venueLabel = get_post_meta($event->ID, 'ot_e_label', true);
			if (!empty($venueLabel)) {
				$venue = $venueLabel;
			} else {
				$venue = $event->post_title;
			}
			$link = get_post_meta($event->ID, 'ot_e_link', true);
			$location = get_post_meta($event->ID, 'ot_e_location', true);
			$details = $event->post_content;
			$time = get_post_meta($event->ID, 'ot_e_time', true);
			$editlink = '';
			if (is_user_logged_in()) {
				$editlink = '<span class="edit_link"><a href="'.get_edit_post_link( $event->ID).'">Edit This Event</a></span>';
			}
			// making sure the eventTime that is used to sort the printed array shows different 	numbers for each event...even if they're on the same day.
			$eventTime = $origDate + rand(1,20);

			// Let's check to see if this date is not already passed:
				$return = '
				<tr class="ot_e_data">
					<td class="e_date">'.$day.'</td>
					<td class="e_location">'.$location.'</td>';
				if ($link) {
					$return .= '<td class="e_venue"><a href="'.$link.'" target="_blank">'.$venue.'</a></td>';
				} else {
					$return .= '<td class="e_venue">'.$venue.'</td>';
				}
				$return .= '<td class="e_details">'.apply_filters('the_content', $details);
				// this should be pretty self-explanitory

				if ($time or $link) {
					$return .= '<span class="event_details">';
					// if time exists:
					if ($time) {
						$return .= '<span class="event_time">'.$time.'</span>';
					}
					$return .= '</span>'.$editlink;
				}
				$return.='</td>
				  </tr>';
				$var[$eventTime] = $return;
		}
	} else {
		$month = date('F', $origDate);
		$day = date('j', $origDate);
		$eventTime = $origDate;
		$venueLabel = get_post_meta($event->ID, 'ot_e_label', true);
		if (!empty($venueLabel)) {
			$venue = $venueLabel;
		} else {
			$venue = $event->post_title;
		}
		$link = get_post_meta($event->ID, 'ot_e_link', true);
		$location = get_post_meta($event->ID, 'ot_e_location', true);
		$details = $event->post_content;
		$time = get_post_meta($event->ID, 'ot_e_time', true);
		$editlink = '';
		if (is_user_logged_in()) {
			$editlink = '<span class="edit_link"><a href="'.get_edit_post_link( $event->ID).'">Edit This Event</a></span>';
		}
		// making sure the eventTime that is used to sort the printed array shows different 	numbers for each event...even if they're on the same day.
		$eventTime = $origDate + rand(1,20);

		// Let's check to see if this date is not already passed:
			$return = '
			<tr class="ot_e_data">
				<td class="e_date">'.$day.'</td>
				<td class="e_location">'.$location.'</td>';
			if ($link) {
				$return .= '<td class="e_venue"><a href="'.$link.'" target="_blank">'.$venue.'</a></td>';
			} else {
				$return .= '<td class="e_venue">'.$venue.'</td>';
			}
			$return .= '<td class="e_details">'.apply_filters('the_content', $details);
			// this should be pretty self-explanitory

			if ($time or $link) {
				$return .= '<span class="event_details">';
				// if time exists:
				if ($time) {
					$return .= '<span class="event_time">'.$time.'</span>';
				}
				$return .= '</span>'.$editlink;
			}
			$return.='</td>
			  </tr>';
			$var[$eventTime] = $return;
	}


	endforeach;
	ksort($var);
	$currentMonth = '';
	foreach($var as $key => $value) {
		$month = date('F',$key);
		if (strcmp($month, $currentMonth) != 0) {
			$master_return .= '
			<tr class="month august">
				<td colspan="5">'.$month.'</td>
			</tr>';
			$currentMonth = $month;
		}
		$master_return .= $value;
	}
	$master_return .= '</table>';
	return $master_return;
}
add_shortcode("events", "ot_calendar_func");


/* Define the custom box for Event Data */
// WP 3.0+
add_action('add_meta_boxes', 'ot_events_meta_box');
// backwards compatible
add_action('admin_init', 'ot_events_meta_box', 1);

/* Do something with the data entered */
add_action('save_post', 'ot_events_save_postdata');

/* Adds a box to the main column on the Post and Page edit screens */
function ot_events_meta_box() {
	add_meta_box( 'ot_events_sectionid', __( 'Event Details', 'ot_events_textdomain' ), 'ot_events_inner_custom_box','event', 'normal', 'high');
}

/* Prints the box content */
function ot_events_inner_custom_box() {

  // Use nonce for verification
  wp_nonce_field( plugin_basename(__FILE__), 'ot_events_noncename' );

	global $post;
	$ot_e_link 		= get_post_meta($post->ID, 'ot_e_link', true);
	$ot_e_date		= get_post_meta($post->ID, 'ot_e_date', true);
	$ot_e_location	= get_post_meta($post->ID, 'ot_e_location', true);
	$ot_e_time		= get_post_meta($post->ID, 'ot_e_time', true);
	$ot_e_label		= get_post_meta($post->ID, 'ot_e_label', true);

  // The actual fields for data entry ?>
<table border="0" cellspacing="5" cellpadding="5" width="100%">
	<tr>
	<td>
		<p>
			<label for="ot_e_label">Event Label</label><br>
			<input type="text" name="ot_e_label" value="<?php echo $ot_e_label; ?>" id="ot_e_label">
		</p>
		<p>
			<label for="ot_e_location">Event Location</label><br>
			<input type="text" name="ot_e_location" value="<?php echo $ot_e_location; ?>" id="ot_e_location">
		</p>
		<p>
			<label for="ot_e_date">Event Date <small>(accepts logical dates, like March 1, 2012, or 03/01/2012)</small></label><br>
			<input type="text" name="ot_e_date" value="<?php if (!empty($ot_e_date)) { echo date('m/d/Y',$ot_e_date); } ?>" id="ot_e_date">
		</p>
		<p>
			<label for="ot_e_link">Event Link: <small>Please include http:// or the link will result in a 404 on your site</small></label><br>
			<input size="60" type="text" name="ot_e_link" value="<?php echo $ot_e_link; ?>" id="ot_e_link">
		</p>
		<p>
			<label for="ot_e_time">Event Time: <small>(Freeform styling, 7pm ET, or 9-5pm)</small></label><br>
			<input type="text" name="ot_e_time" value="<?php echo $ot_e_time; ?>" id="ot_e_time">

		</p>
	</td>
	</tr>
</table>

<?php
}

/**
 * When the post is saved, saves our custom data
 */
function ot_events_save_postdata( $post_id ) {

	// verify this came from the our screen and with proper authorization
	// because save_post can be triggered at other times.
	$ot_events_noncename = isset(  $_POST['ot_events_noncename'] ) ? $_POST['ot_events_noncename'] : '';
	if ( ! wp_verify_nonce( $ot_events_noncename, plugin_basename( __FILE__ ) ) ) {
		return $post_id;
	}

if ( isset( $_POST['ot_events_noncename'] ) && !wp_verify_nonce( $_POST['ot_events_noncename'], plugin_basename(__FILE__) ) )
      return $post_id;
  // verify if this is an auto save routine.
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
      return $post_id;

	// Check permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

  // OK, we're authenticated: we need to find and save the data

	if (!empty($_POST['ot_e_location'])) {
	$ot_e_location = $_POST['ot_e_location'];
	}

	if (! empty( $_POST['ot_e_label'] ) ) {
		$ot_e_label = $_POST['ot_e_label']; // phpcs:ignore
	}

	if ( ! empty( $_POST['ot_e_date'] ) ) {
		$ot_e_date = strtotime($_POST['ot_e_date']); // phpcs:ignore
	}
	if ( ! empty( $_POST['ot_e_link'] ) ) {
		$ot_e_link = $_POST['ot_e_link']; // phpcs:ignore
	}
	if ( ! empty( $_POST['ot_e_time'] ) ) {
		$ot_e_time = $_POST['ot_e_time']; // phpcs:ignore
	}

	// update the data.
	update_post_meta( $post_id, 'ot_e_location', $ot_e_location) ;
	update_post_meta( $post_id, 'ot_e_date', $ot_e_date );
	update_post_meta( $post_id, 'ot_e_label', $ot_e_label );
	update_post_meta( $post_id, 'ot_e_link', $ot_e_link );
	update_post_meta( $post_id, 'ot_e_time', $ot_e_time );
}
?>