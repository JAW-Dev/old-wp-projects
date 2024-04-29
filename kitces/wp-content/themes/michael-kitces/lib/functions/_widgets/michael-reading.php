<?php

class What_Michael_Reading_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'what_michael_is_reading_widget',
			'What Michael is Reading Widget',
			array( 'description' => 'Add an affiliate link to a book on Amazon.' )
		);
	}

	public function widget( $args, $instance ) {
		$widget_id            = $args['widget_id'];
		$widget_title         = get_field( 'widget_title', 'widget_' . $widget_id );
		$book_title           = get_field( 'book_title', 'widget_' . $widget_id );
		$book_author          = get_field( 'book_author', 'widget_' . $widget_id );
		$plain_affiliate_link = get_field( 'plain_affiliate_link', 'widget_' . $widget_id );
		$image_markup         = get_field( 'image_markup', 'widget_' . $widget_id );
		$final_cta_btn        = get_field( 'block_cta_button', 'widget_' . $widget_id );

		echo $args['before_widget'];
		?>
		<?php if ( ! empty( $widget_title ) ) : ?>
			<h4 class="widget-title widgettitle"><?php echo $widget_title; ?></h4>
		<?php endif; ?>
		<div class="row">
			<?php if ( ! empty( $image_markup ) ) : ?>
				<div class="one-half first">
					<?php echo $image_markup; ?>
				</div>
			<?php endif; ?>
			<?php if ( ! empty( $plain_affiliate_link ) ) : ?>
				<div class="one-half">
					<p style="color: #005392; font-size: 18px; margin-bottom: 0; margin-top: 15px; font-weight: bold;"><a href="<?php echo $plain_affiliate_link; ?>" target="blank"> <?php echo $book_title; ?></a></p>
					<p class="font-14" style="color: #7d7d7d;">- <?php echo $book_author; ?></p>
					<a class="more-link" style="padding: 10px; font-weight: 400; margin: 4px; margin-bottom: 10px; font-size: 10px; text-transform: uppercase;" href="<?php echo $plain_affiliate_link; ?>" target="blank"> get it now</a>
				</div>
			<?php endif; ?>
			<div class="clear"></div>
			<?php if ( ! empty( $final_cta_btn ) ) : ?>
				<div class="optin wmr-footer tac">
					<?php echo mk_link_html( $final_cta_btn, 'button' ); ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
		echo $args['after_widget'];
	}

	public function form( $instance ) {
	}

	public function update( $new_instance, $old_instance ) {
		$instance = array();
		return $instance;
	}
}
