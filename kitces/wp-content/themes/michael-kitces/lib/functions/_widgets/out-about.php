<?php

class Out_And_About_Widget extends WP_Widget {


	function __construct() {
		parent::__construct(
			'out_and_about_widget',
			'Out And About Sidebar Widget',
			array( 'description' => 'Display the Out And About Events in the sidebar.' )
		);
	}

	public function widget( $args, $instance ) {
		$today = date( 'Y/m/d' );
		$one_month = date('Y/m/d', strtotime("$today +1 month"));
		$events = obj_get_ot_events( $today, $one_month, 3 );

		$events = obj_flat_events_array( $events );

		if ( ! empty( $events ) && is_array( $events ) ) {

			echo $args['before_widget'];

			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}

			echo "<div class='events-widget__events-wrap'>";
			foreach ( $events as $event_id ) {
				$date          = obj_get_event_start_date( $event_id );
				$month_string  = date_i18n( 'M', $date );
				$day_string    = date_i18n( 'j', $date );
				$speaker_class = obj_get_speaker_class( $event_id );
				$speaker_name  = obj_get_speaker_name( $event_id );
				$location      = obj_get_event_location( $event_id );
				$link          = obj_get_event_link( $event_id );
				$title         = obj_get_event_title( $event_id );

				if ( ! empty( $title ) ) {
				?>
					<div class="events-widget__event <?php echo $speaker_class; ?>">
						<div class="events-widget__left-side">
							<?php if ( ! empty( $month_string ) && ! empty( $day_string ) ) : ?>
								<div class="events-widget__event-date">
									<span class="events-widget__event-date-inner tac">
										<?php echo $month_string; ?>
										<br>
										<?php echo $day_string; ?>
									</span>
								</div>
							<?php endif; ?>
						</div>
						<div class="events-widget__right-side">
							<?php if ( ! empty( $title ) ) : ?>
								<div class="events-widget__event-title"><?php echo $title; ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $speaker_name ) ) : ?>
								<div class="events-widget__event-speaker">Speaker: <?php echo $speaker_name; ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $location ) ) : ?>
								<div class="events-widget__event-location">Location: <?php echo $location; ?></div>
							<?php endif; ?>
							<?php if ( ! empty( $link ) && array_key_exists( 'url', $link ) && ! empty( $link['url'] ) ) : ?>
								<a target="<?php echo $link['target']; ?>" href="<?php echo $link['url']; ?>" class="events-widget__event-link">Event Details</a>
							<?php endif; ?>
						</div>
					</div>
					<?php
				}
			}

			echo '</div>';

			?>
				<div style="margin-top: 20px;">
					<a href="/schedule/" class="more-link">View Full Schedule</a>
				</div>
			<?php

			echo $args['after_widget'];

		}

	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php

	}

	public function update( $new_instance, $old_instance ) {
		$instance                    = array();
		$instance['title']           = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['number_of_posts'] = ( ! empty( $new_instance['number_of_posts'] ) ) ? strip_tags( $new_instance['number_of_posts'] ) : '';

		return $instance;
	}

}
