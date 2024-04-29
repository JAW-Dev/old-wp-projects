<?php

class Announcements_Ajax_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'class_name'  => 'announcement_widget_ajax widget',
			'description' => 'Display announcements pulled in via ajax.',
		);
		parent::__construct( 'announcement_widget_ajax', 'Announcements Widget - Ajax', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		echo '<!--googleoff: index-->';
		if ( function_exists( 'get_field' ) ) {
			// Get the id
			$widget_id = $args['widget_id'];

			// Get the Fields
			$announcement_location = get_field( 'announcement_location', 'widget_' . $widget_id );

			if ( ! empty( $announcement_location ) ) {
				echo $args['before_widget'];
				?>
				<div class="inner-ajax-notification" data-notification-location="<?php echo $announcement_location; ?>"></div>
				<?php
				echo $args['after_widget'];
			}
		}
		echo '<!--googleon: index -->';
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		?>
		<h2>Announcements Widget - Ajax</h2>
		<p>Displays announcements set up in WP Admin > Theme Settings > Announcements.</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		return $instance;
	}
}

// Call back for Ajax triggered from this widget
add_action( 'wp_ajax_announcements_callback', 'mk_announcements_callback' );
add_action( 'wp_ajax_nopriv_announcements_callback', 'mk_announcements_callback' );
function mk_announcements_callback() {
	$notification_location = $_POST['notificationLocation'];

	$return_data = array(
		'html'    => null,
		'success' => false,
		'count'   => 0,
	);

	if ( ! empty( $notification_location ) ) {
		$notifications_details = mk_get_announcements_html( $notification_location );
		$notifications_html    = mk_key_value( $notifications_details, 'html' );
		$notifications_count   = mk_key_value( $notifications_details, 'count' );

		if ( ! empty( $notifications_html ) ) {
			$return_data['html']    = $notifications_html;
			$return_data['success'] = true;
			$return_data['count']   = $notifications_count;
		}
	}

	echo wp_json_encode( $return_data );
	wp_die();
}

function mk_get_announcements_html( $location = null ) {

	$announcements = mk_get_field( 'announcements_to_display', 'announcements-settings', true, true );
	$count         = 0;
	ob_start();
	?>
	<?php if ( ! empty( $location ) && ! empty( $announcements ) && is_array( $announcements ) ) : ?>
			<!--googleoff: index-->
				<?php foreach ( $announcements as $a ) : ?>
					<?php
						$display_options       = mk_key_value( $a, 'display_options' );
						$notification_location = mk_key_value( $a, 'notification_location' );
						$announcement_blurb    = mk_key_value( $a, 'announcement_blurb' );
						$style                 = mk_key_value( $a, 'style' );
						$background_color      = mk_key_value( $a, 'background_color' );
						$text_color            = mk_key_value( $a, 'text_color' );
						$link_color            = mk_key_value( $a, 'link_color' );
						$exclamation_mark      = mk_key_value( $a, 'include_exclamation_mark' );
						$announcement_id       = 'aa' . md5( $announcement_blurb );
						$css_id                = '#' . $announcement_id;

						$output_announcement = false;
					if ( $location === $notification_location ) {
						if ( 'schedule' === $display_options ) {
							$start_display     = mk_key_value( $a, 'start_display' );
							$stop_display      = mk_key_value( $a, 'stop_display' );
							$current_date_time = wp_date( 'Y-m-d H:i:s' );
							$compare_current   = strtotime( $current_date_time );
							$compare_start     = strtotime( $start_display );

							if ( ! empty( $start_display ) && empty( $stop_display ) ) {
								if ( $compare_current >= $compare_start ) {
									$output_announcement = true;
								}
							} elseif ( ! empty( $start_display ) && ! empty( $stop_display ) ) {
								$compare_stop = strtotime( $stop_display );
								if ( $compare_current >= $compare_start && $compare_current <= $compare_stop ) {
									$output_announcement = true;
								}
							}
						} elseif ( 'on' === $display_options ) {
							$output_announcement = true;
						}

						if ( isset( $_COOKIE[ $announcement_id ] ) || 'off' === $display_options ) {
							$output_announcement = false;
						}
					}
					?>
					<?php if ( $output_announcement ) : ?>
						<?php if ( 'custom' === $style && ! empty( $background_color ) && ! empty( $text_color ) ) : ?>
							<style>
								<?php echo $css_id; ?>.announcement .inner.custom {
									background: <?php echo $background_color; ?>;
									color: <?php echo $text_color; ?>;
								}
								<?php if ( ! empty( $link_color ) ) : ?>
									<?php echo $css_id; ?>.announcement .inner.custom a {
									color: <?php echo $link_color; ?>;
								}
								<?php endif; ?>
							</style>
						<?php endif; ?>
						<div id="<?php echo $announcement_id; ?>" class="announcement hidden">
							<div class="inner <?php echo $style; ?>">
								<?php if ( ! empty( $announcement_blurb ) ) : ?>
									<?php if ( $exclamation_mark ) : ?>
										<span class="exclamation">!</span>
									<?php endif; ?>
									<div class="announcement-content last-child-margin-bottom-0">
										<?php echo $announcement_blurb; ?>
									</div>
									<span class="announcement-close">x</span>
								<?php endif; ?>
							</div>
						</div>
						<?php $count++; ?>
					<?php endif; ?>
				<?php endforeach; ?>
			<!--googleon: index -->
		<?php
	endif;

	$html = ob_get_contents();
	ob_end_clean();

	$count_odd_even = 'odd';
	if ( $count % 2 == 0 ) {
		$count_odd_even = 'even';
	}

	return array(
		'html'  => $html,
		'count' => $count_odd_even,
	);
}
