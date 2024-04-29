<?php

class OBJ_CTA_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'class_name'  => 'obj_cta widget',
			'description' => 'Displays a CTA.',
		);
		parent::__construct( 'objectiv_cta_widget', 'Call To Action', $widget_ops );
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
		// Get the id
		$widget_id = $args['widget_id'];

		// Get the Fields
		$title           = get_field( 'title', 'widget_' . $widget_id );
		$blurb           = get_field( 'blurb', 'widget_' . $widget_id );
		$button          = get_field( 'button', 'widget_' . $widget_id );
		$logged_out_only = get_field( 'only_show_for_logged_out_users', 'widget_' . $widget_id );
		$display_widget  = true;

		if ( $logged_out_only && ! is_user_logged_in() ) {
			$display_widget = true;
		} elseif ( $logged_out_only && is_user_logged_in() ) {
			$display_widget = false;
		}

		if ( $display_widget ) {
			echo $args['before_widget'];
			?>

			<?php if ( ! empty( $title ) ) : ?>
				<h4 class="widget-title widgettitle"><?php echo $title; ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $blurb ) ) : ?>
				<div class="cta-widget-blurb"><?php echo $blurb; ?></div>
			<?php endif; ?>

			<?php if ( ! empty( $button ) ) : ?>
				<?php echo objectiv_link_button( $button, 'red-button' ); ?>
			<?php endif; ?>

			<?php
			echo $args['after_widget'];
		}

	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {
	?>
		<h2>CTA Widget</h2>
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
