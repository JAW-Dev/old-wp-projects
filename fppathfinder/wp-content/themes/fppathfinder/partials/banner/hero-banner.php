<?php

use FP_Core\InteractiveLists\Tables\LinkShare;

$download_page = false;

// Figure the Title and Sub Title Out
$title = get_field( 'banner_title' );
if ( empty( $title ) ) {
	$title = decide_banner_title();
}
$subtitle         = get_field( 'banner_sub_title' );
$button           = get_field( 'banner_button' );
$secondary_button = get_field( 'banner_secondary_button' );

// Set up thumbnail
$bg_image_url = decide_banner_bg_img();
$bg_class     = 'blue';
if ( ! empty( $bg_image_url ) ) :
	$bg_class = 'dark-gray';
endif;

$hide_banner = get_field( 'display_banner_setting' );

if ( is_singular( 'download' ) && function_exists( 'rcp_get_content_subscription_levels' ) ) {
	$pid            = get_the_ID();
	$current_levels = get_current_item_levels_links( $pid );
	$current_cats   = get_current_download_cat_links( $pid );
	$download_page  = true;
	$cats_title     = 'Category: ';


	if ( ! empty( $current_levels ) ) {
		$levels_keys = array_keys( $current_levels );
		$levels_last = array_pop( $levels_keys );
	}

	if ( ! empty( $current_cats ) ) {
		$cats_keys = array_keys( $current_cats );
		$cats_last = array_pop( $cats_keys );
		if ( count( $current_cats ) > 1 ) {
			$cats_title = 'Categories: ';
		}
	}
}

$custom_bg    = '';
$logo         = '';
$advisor_name = '';
$phone        = '';
$email        = '';

if ( fp_is_share_link() ) {
	global $wpdb;

	$share_key  = sanitize_text_field( wp_unslash( $_GET['sh'] ?? '' ) );
	$table      = LinkShare::get_resource_share_link_table_name();
	$entry      = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table WHERE share_key = %s", $share_key ), ARRAY_A ); // phpcs:ignore
	$advisor_id = ! empty( $entry['advisor_user_id'] ) ? $entry['advisor_user_id'] : '';
	$settings   = fp_get_whitelabel_settings( fp_get_user_settings( (int) $advisor_id ) );
	$logo       = ! empty( $settings['logo'] ) ? $settings['logo'] : '';
	$color      = ! empty( $settings['color_set']['color1'] ) ? $settings['color_set']['color1'] : '';

	if ( fp_is_share_link() ) {
		$settings = fp_get_share_link_settings( fp_get_user_settings( (int) $advisor_id ) );
	}

	$advisor_name = ! empty( $settings['advisor_name'] ) ? $settings['advisor_name'] : '';
	$phone        = ! empty( $settings['share_link_phone'] ) ? $settings['share_link_phone'] : '';
	$email        = ! empty( $settings['share_link_email'] ) ? $settings['share_link_email'] : '';

	if ( ! empty( $color ) ) {
		$custom_bg = "background-color: $color;";
	}
}

?>

<?php if ( ! $hide_banner ) : ?>
	<section class="page-banner <?php echo esc_attr( $bg_class ); ?>">

		<?php if ( ! empty( $bg_image_url ) ) : ?>
			<div class="page-banner__image" style="background-image: url(<?php echo esc_url( $bg_image_url ); ?>)"></div>
		<?php endif; ?>

		<div class="wrap">
			<?php if ( fp_is_share_link() ) : ?>
				<div class="page-banner__content lmb0">
					<div class="page-banner__title-wrap" style="<?php echo esc_attr( $custom_bg ); ?>">
						<h1 class="page-banner__title"><?php echo esc_html( $title ); ?></h1>
					</div>
					<div class="page-banner__logo">
						<img src="<?php echo esc_attr( $logo ); ?>" />

						<?php if ( ! empty( $advisor_name ) ) : ?>
							<p><em>Your Advisor</em></p>
							<div><strong><?php echo esc_html( $advisor_name ); ?></strong></div>
						<?php endif; ?>

						<?php if ( ! empty( $email ) ) : ?>
							<div><?php echo esc_html( $email ); ?></div>
						<?php endif; ?>
						<?php if ( ! empty( $phone ) ) : ?>
							<div><?php echo esc_html( $phone ); ?></div>
						<?php endif; ?>
					</div>
				</div>
			<?php else : ?>
				<div class="page-banner__content lmb0">
					<h1 class="page-banner__title"><?php echo $title; ?></h1>

					<?php if ( ! empty( $subtitle ) ) : ?>
						<p class="page-banner__subtitle"><?php echo $subtitle; ?></p>
					<?php endif; ?>

					<?php if ( ! empty( $current_levels ) && $download_page ) : ?>
						<p class="page-banner__subtitle">
							Membership Package:
							<?php foreach ( $current_levels as $k => $current_level ) : ?>
								<?php if ( $k !== $levels_last ) : ?>
									<?php echo ( $current_level . ', ' ); ?>
								<?php else : ?>
									<?php echo $current_level; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $current_cats ) && $download_page ) : ?>
						<p class="page-banner__subtitle">
							<?php echo $cats_title; ?>
							<?php foreach ( $current_cats as $i => $cat_link ) : ?>
								<?php if ( $i !== $cats_last ) : ?>
									<?php echo ( $cat_link . ', ' ); ?>
								<?php else : ?>
									<?php echo $cat_link; ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</p>
					<?php endif; ?>

					<?php if ( ! empty( $button ) || ! empty( $secondary_button ) ) : ?>
						<div class="banner-buttons">

							<?php if ( ! empty( $button ) ) : ?>
								<?php echo objectiv_link_button( $button, 'red-button' ); ?>
							<?php endif; ?>

							<?php if ( ! empty( $secondary_button ) ) : ?>
								<?php echo objectiv_link_button( $secondary_button, 'transparent-button' ); ?>
							<?php endif; ?>

						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php do_action( 'obj_before_banner_close' ); ?>
	</section>
<?php endif; ?>
