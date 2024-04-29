<?php

class Announcements_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'class_name'  => 'announcement_widget widget',
			'description' => 'Display announcements at the top of the blog archive as well as the top of single blog posts.',
		);
		parent::__construct( 'announcement_widget', 'Announcements Widget', $widget_ops );
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
			$announcement_blurb = get_field( 'announcement_blurb', 'widget_' . $widget_id );
			$style              = get_field( 'style', 'widget_' . $widget_id );
			$background_color   = get_field( 'background_color', 'widget_' . $widget_id );
			$text_color         = get_field( 'text_color', 'widget_' . $widget_id );
			$link_color         = get_field( 'link_color', 'widget_' . $widget_id );
			$exclamation_mark   = get_field( 'include_exclamation_mark', 'widget_' . $widget_id );

			if ( 'custom' === $style && ! empty( $background_color ) && ! empty( $text_color ) ) {
				$css_id = '#' . $widget_id;
				?>
				<style>
					<?php echo $css_id; ?>.announcement .inner {
						background: <?php echo $background_color; ?>;
						color: <?php echo $text_color; ?>;
					}
					<?php if ( ! empty( $link_color ) ) : ?>
						<?php echo $css_id; ?>.announcement .inner a {
							color: <?php echo $link_color; ?>;
						}
					<?php endif; ?>
				</style>
				<?php
			}

			echo $args['before_widget'];
			?>

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

			<?php
			echo $args['after_widget'];
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
		<h2>Announcements Widget</h2>
		<p>Displays an announcement on the blog.</p>
		<br/>
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
