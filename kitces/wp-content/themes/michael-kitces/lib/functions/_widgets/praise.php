<?php

class Nerds_Eye_View_Praise_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'nerds_eye_view_praise_widget',
			'Nerd\'s Eye View Praise Widget',
			array( 'description' => 'Display the Nerd\'s Eye View Praise Widget in the sidebar.' )
		);
	}

	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		$query_args = array(
			'post_type'      => 'testimonials',
			'posts_per_page' => '-1',
		);

		$testimonials = new WP_Query( $query_args );

		if ( $testimonials->have_posts() ) {
			echo '<div class="nerds-eye-view-testimonials">';
			while ( $testimonials->have_posts() ) {
				$testimonials->the_post();
				$prefix = '_cgd_';
				$link   = get_post_meta( get_the_ID(), $prefix . 'testimonial_posttype_link', true );
				$text   = get_post_meta( get_the_ID(), $prefix . 'testmionial_posttype_text', true );
				$image  = get_post_meta( get_the_ID(), $prefix . 'testimonial_posttype_sidebar_image', true );
				?>
				<div class="nerds-eye-view-testimonial">
					<?php if ( ! empty( $link ) ) : ?>
							<a href="<?php echo $link; ?>" target="_blank">
								<div class="nerds-eye-view-testimonial-image">
									<img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
								</div>
								<p class="nerds-eye-view-testimonial-text">&ldquo;<?php echo $text; ?>&rdquo;</p>
							</a>
						<?php else : ?>
							<div class="nerds-eye-view-testimonial-image">
								<img src="<?php echo $image; ?>" alt="<?php the_title(); ?>">
							</div>
							<p class="nerds-eye-view-testimonial-text">&ldquo;<?php echo $text; ?>&rdquo;</p>
						<?php endif; ?>
					</div>
					<?php
					wp_reset_postdata();
			}
			echo '</div>';
		} else {
			echo 'No Testimonials Found';
		}
		echo $args['after_widget'];
	}

	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : 'New title';

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;
	}
}
