<?php

class OBJ_Member_Welcome_Widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
		$widget_ops = array(
			'class_name'  => 'obj_member_welcome widget',
			'description' => 'Displays welcome for signed in users.',
		);
		parent::__construct( 'objectiv_member_welcome_widget', 'Member Welcome', $widget_ops );
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
		// $title = get_field( 'title', 'widget_' . $widget_id );
		$title              = 'Welcome, Guest';
		$account_page       = get_field( 'my_account_page', 'option' );
		$white_label_page   = get_field( 'white_label_page', 'option' );
		$first_name         = obj_get_users_first_name();
		$can_white_label    = \FP_PDF_Generator\Customization_Controller::user_can_save_white_label_settings( get_current_user_id() );
		$more_links         = get_field( 'links_list', 'widget_' . $widget_id );
		$logged_out_blurb   = get_field( 'logged_out_blurb', 'widget_' . $widget_id );
		$logged_out_button  = get_field( 'logged_out_button', 'widget_' . $widget_id );
		$user_id            = get_current_user_id();
		$member             = $user_id ? new \FP_Core\Member( $user_id ) : false;
		$can_access_bundles = current_user_can( 'administrator' ) || rcp_user_has_access( 0, 4 ) || ( $member && ( $member->is_active_at_level( FP_ENTERPRISE_DELUXE_ID ) || $member->is_active_at_level( FP_ENTERPRISE_PREMIER_ID ) ) );
		$guid_bundles       = function_exists( 'get_field' ) ? get_field( 'fp_guide_bundles_link', 'option' ) : '';

		if ( $account_page && ! empty( $first_name ) ) {
			$ac_permalink = get_permalink( $account_page );

			if ( ! empty( $ac_permalink ) ) {
				$title = "Welcome, <a href='{$ac_permalink}'>{$first_name}</a>";
			}
		}

		if ( ! empty( $title ) ) {

			echo $args['before_widget'];
			?>

			<?php if ( ! empty( $title ) ) : ?>
				<h4 class="widget-title widgettitle"><?php echo $title; ?></h4>
			<?php endif; ?>

			<?php if ( ! empty( $account_page ) ) : ?>
				<div class="white-label-link__wrap">
					<?php if ( $can_white_label ) : ?>
						<a href="<?php echo get_the_permalink( $white_label_page ); ?>">White Label Settings</a>
						<?php obj_svg( 'pad-unlocked' ); ?>
					<?php else : ?>
						<a href="<?php echo fp_get_upsell_modal_link( 'white_label' ); ?>" class="upsell-modal">White Label Settings</a>
						<?php obj_svg( 'pad-locked' ); ?>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="white-label-link__wrap">
				<?php if ( $can_access_bundles ) : ?>
					<a href="/download-bundles">One-Click Downloads</a>
					<?php obj_svg( 'pad-unlocked' ); ?>
				<?php else : ?>
					<a href="<?php echo fp_get_upsell_modal_link( 'one_click' ); ?>" class="upsell-modal">One-Click Downloads</a>
					<?php obj_svg( 'pad-locked' ); ?>
				<?php endif; ?>
			</div>

			<?php if ( is_array( $more_links ) && ! empty( $more_links ) ) : ?>
				<?php foreach ( $more_links as $link ) : ?>
					<div class="white-label-link__wrap"><?php echo objectiv_link_link( $link['link'] ); ?></div>
				<?php endforeach; ?>
			<?php endif; ?>

			<?php if ( ! empty( $logged_out_blurb ) && ! is_user_logged_in() ) : ?>
				<div class="logged-out-blurb"><?php echo $logged_out_blurb; ?></div>
				<?php if ( ! empty( $logged_out_button ) ) : ?>
					<?php echo objectiv_link_button( $logged_out_button, 'button red-button' ); ?>
				<?php endif; ?>
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
		<h2>Member Welcome Widget</h2>
		<p>Displays a welcome message for logged in members.</p>
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
