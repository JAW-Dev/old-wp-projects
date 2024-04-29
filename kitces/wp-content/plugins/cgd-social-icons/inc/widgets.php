<?php

class CGD_Social_Header extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cgd_social_header',
			'description' => 'Displays the four social icons in the header.',
		);
		parent::__construct( 'cgd_social_header', 'Social Links - Header', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$social_links_header = array(
			array(
				'platform' => 'facebook',
				'url'	=> 'https://www.facebook.com/Kitces',
				'alt'		=> 'Kitces.com on Facebook'
			 ),
			array(
				'platform' => 'twitter',
				'url'	=> 'https://twitter.com/MichaelKitces',
				'alt'		=> '@MichaelKitces on Twitter'
			 ),
			array(
				'platform' => 'linkedin',
				'url'	=> 'https://www.linkedin.com/in/michaelkitces',
				'alt'		=> 'Michael Kitces on LinkedIn'
			 ),
			array(
				'platform' => 'youtube',
				'url'	=> 'http://www.youtube.com/user/MichaelKitces?feature=watch',
				'alt'		=> 'Michael Kitces YouTube Channel'
			 )
		);

		foreach ( $social_links_header as $link ) {
			$platform = $link['platform'];
			$url = $link['url'];
			$alt = $link['alt'];

			$output .= '<li class="cgd-social-link ' . $platform . '"><a href="' . $url . '" target="_blank">';
			$output .= '<img alt="' . $alt . '" src="/wp-content/plugins/cgd-social-icons/icons/icons-' . $platform . '.png">';
			$output .= '</a></li>';
		}

		echo $args['before_widget'];
		echo '<ul class="cgd-social-icons">' . $output . '</ul>';
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		?>
		<p>This widget displays four social media icons in the header of the site.</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'CGD_Social_Header' );
});

class CGD_Social_Footer extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'classname' => 'cgd_social_footer',
			'description' => 'Displays the four social icons in the footer.',
		);
		parent::__construct( 'cgd_social_footer', 'Social Links - Footer', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// outputs the content of the widget
		$social_links_footer = array(
			array(
				'platform' => 'facebook',
				'url'	=> 'https://www.facebook.com/Kitces',
				'alt'		=> 'Kitces.com on Facebook'
			 ),
			array(
				'platform' => 'twitter',
				'url'	=> 'https://twitter.com/MichaelKitces',
				'alt'		=> '@MichaelKitces on Twitter'
			 ),
			array(
				'platform' => 'linkedin',
				'url'	=> 'https://www.linkedin.com/in/michaelkitces',
				'alt'		=> 'Michael Kitces on LinkedIn'
			 ),
			 array(
				 'platform' => 'pinterest',
				 'url'	=> 'https://www.pinterest.com/michaelkitces/',
				 'alt'		=> 'Michael Kitces on Pinterest'
			 ),
			array(
				'platform' => 'youtube',
				'url'	=> 'http://www.youtube.com/user/MichaelKitces?feature=watch',
				'alt'		=> 'Michael Kitces YouTube Channel'
			),
			array(
				'platform' => 'rss',
				'url'	=> 'http://feeds.kitces.com/KitcesNerdsEyeView',
				'alt'		=> 'Michael Kitces RSS Feed'
			),
			array(
				'platform' => 'phone',
				'url'	=> '/contact',
				'alt'		=> 'Call Michael Kitces'
			),
			array(
				'platform' => 'email',
				'url'	=> '/contact',
				'alt'		=> 'Email Michael Kitces'
			)
		);

		$output = '';

		foreach ( $social_links_footer as $key => $link ) {
			$platform = $link['platform'];
			$url = $link['url'];
			$alt = $link['alt'];

			$output .= '<li class="cgd-social-link ' . $platform . '"><a href="' . $url . '" target="_blank">';
			$output .= '<img alt="' . $alt . '" src="/wp-content/plugins/cgd-social-icons/icons/icons-' . $platform . '.png">';
			$output .= '</a></li>';
		}

		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		}
		echo '<ul class="cgd-social-icons">' . $output . '</ul>';
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
		// outputs the options form on admin
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'New title', 'text_domain' );
		?>
		<p>This widget displays four social media icons in the footer of the site.</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		// processes widget options to be saved
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}

add_action( 'widgets_init', function(){
	register_widget( 'CGD_Social_Footer' );
});
