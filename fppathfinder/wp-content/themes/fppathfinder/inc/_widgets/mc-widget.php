<?php

class OBJ_MailChimp_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'class_name'  => 'obj_mailchimp widget',
			'description' => 'Displays Subscription form.',
		);
		parent::__construct( 'objectiv_mailchimp_widget', 'Mailchimp Form', $widget_ops );
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
		$title        = get_field( 'title', 'widget_' . $widget_id );
		$blurb        = get_field( 'blurb', 'widget_' . $widget_id );
		$gravity_form = get_field( 'gravity_form', 'widget_' . $widget_id );

		if ( ! empty( $gravity_form ) ) {

			echo $args['before_widget'];
			?>

			<?php if ( ! empty( $title ) ) : ?>
				<h4 class="widget-title widgettitle"><?php echo $title; ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $blurb ) ) : ?>
				<div class="mc-widget-blurb"><?php echo $blurb; ?></div>
			<?php endif; ?>

			<?php
				gravity_form_enqueue_scripts( $gravity_form['id'], true );
				gravity_form( $gravity_form['id'], false, false, false, '', true, 1 );

			?>

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
		<h2>Email Subscription Widget</h2>
		<p>Displays an Email Form based on the settings below.</p>
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
