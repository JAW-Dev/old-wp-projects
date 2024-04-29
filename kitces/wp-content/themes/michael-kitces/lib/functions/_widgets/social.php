<?php

class Mobile_Menu_Social_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'mobile_menu_social_widget',
			'Mobile Menu Social Widget',
			array( 'description' => 'Display the social media links in the mobile Menu.' )
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}
		echo '<ul class="cgd-social-links">';
		if ( ! empty( $instance['facebook'] ) ) {
			echo '<li class="cgd-social-link"><a href="' . $instance['facebook'] . '" target="_blank"><i class="fas fa-facebook-square"></i> Facebook</a></li>';
		}
		if ( ! empty( $instance['twitter'] ) ) {
			echo '<li class="cgd-social-link"><a href="' . $instance['twitter'] . '" target="_blank"><i class="fas fa-twitter-square"></i> Twitter</a></li>';
		}
		if ( ! empty( $instance['linkedin'] ) ) {
			echo '<li class="cgd-social-link"><a href="' . $instance['linkedin'] . '" target="_blank"><i class="fas fa-linkedin-square"></i> LinkedIn</a></li>';
		}
		if ( ! empty( $instance['google'] ) ) {
			echo '<li class="cgd-social-link"><a href="' . $instance['google'] . '" target="_blank"><i class="fas fa-google-plus-square"></i> Google+</a></li>';
		}
		if ( ! empty( $instance['youtube'] ) ) {
			echo '<li class="cgd-social-link"><a href="' . $instance['youtube'] . '" target="_blank"><i class="fas fa-youtube-square"></i> YouTube</a></li>';
		}
		if ( ! empty( $instance['pinterest'] ) ) {
			echo '<li class="cgd-social-link"><a href="' . $instance['pinterest'] . '" target="_blank"><i class="fas fa-pinterest-square"></i> Pinterest</a></li>';
		}
		echo '</ul>';
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title     = ! empty( $instance['title'] ) ? $instance['title'] : 'New title';
		$facebook  = ! empty( $instance['facebook'] ) ? $instance['facebook'] : 'Facebook';
		$twitter   = ! empty( $instance['twitter'] ) ? $instance['twitter'] : 'Twitter';
		$linkedin  = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : 'Linked In';
		$google    = ! empty( $instance['google'] ) ? $instance['google'] : 'Google+';
		$youtube   = ! empty( $instance['youtube'] ) ? $instance['youtube'] : 'YouTube';
		$pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : 'Pinterest';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'facebook' ); ?>"><?php _e( 'Facebook:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'facebook' ); ?>" name="<?php echo $this->get_field_name( 'facebook' ); ?>" type="text" value="<?php echo esc_attr( $facebook ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter' ); ?>"><?php _e( 'Twitter:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'twitter' ); ?>" name="<?php echo $this->get_field_name( 'twitter' ); ?>" type="text" value="<?php echo esc_attr( $twitter ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'linkedin' ); ?>"><?php _e( 'LinkedIn:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'linkedin' ); ?>" name="<?php echo $this->get_field_name( 'linkedin' ); ?>" type="text" value="<?php echo esc_attr( $linkedin ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'google' ); ?>"><?php _e( 'Google+:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'google' ); ?>" name="<?php echo $this->get_field_name( 'google' ); ?>" type="text" value="<?php echo esc_attr( $google ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'youtube' ); ?>"><?php _e( 'YouTube:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'youtube' ); ?>" name="<?php echo $this->get_field_name( 'youtube' ); ?>" type="text" value="<?php echo esc_attr( $youtube ); ?>">
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'pinterest' ); ?>"><?php _e( 'Pinterest:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'pinterest' ); ?>" name="<?php echo $this->get_field_name( 'pinterest' ); ?>" type="text" value="<?php echo esc_attr( $pinterest ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance              = array();
		$instance['title']     = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['facebook']  = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ) : '';
		$instance['twitter']   = ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ) : '';
		$instance['linkedin']  = ( ! empty( $new_instance['linkedin'] ) ) ? strip_tags( $new_instance['linkedin'] ) : '';
		$instance['google']    = ( ! empty( $new_instance['google'] ) ) ? strip_tags( $new_instance['google'] ) : '';
		$instance['youtube']   = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ) : '';
		$instance['pinterest'] = ( ! empty( $new_instance['pinterest'] ) ) ? strip_tags( $new_instance['pinterest'] ) : '';

		return $instance;
	}
}
