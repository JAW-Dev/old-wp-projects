<?php

class OBJ_Download_Cats_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'class_name'  => 'obj_download_cat widget',
			'description' => 'Displays a list of Download Categories',
		);
		parent::__construct( 'objectiv_download_cat_widget', 'Download Categories', $widget_ops );
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
		$title = get_field( 'title', 'widget_' . $widget_id );
		$cats  = get_terms(
			array(
				'taxonomy'   => 'download-cat',
				'hide_empty' => true,
			)
		);

		if ( empty( $title ) ) {
			$title = 'Download Categories';
		}

		if ( ! empty( $cats ) ) {
			echo $args['before_widget'];
			?>

			<?php if ( ! empty( $title ) ) : ?>
				<h4 class="widget-title widgettitle"><?php echo $title; ?></h4>
			<?php endif; ?>

			<ul>
				<?php foreach ( $cats as $c ) : ?>
					<li class="cat-item">
						<a href="<?php echo esc_url( get_term_link( $c ) ); ?>"><?php echo esc_html( $c->name ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>

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
		<h2>Download Categories Widget</h2>
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
