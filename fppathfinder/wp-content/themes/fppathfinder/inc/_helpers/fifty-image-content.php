<?php

function objectiv_fifty_image_content( $row = null ) {

	$image_id      = $row['5050_initial_image']['ID'] ?? '';
	$disp_image    = wp_get_attachment_image( $image_id, 'obj_fifty_cont', false, array( 'class' => 'fifty-image-content__image' ) );
	$pop_image     = $row['5050_pop_up_image']['url'] ?? '';
	$title         = $row['5050_title'] ?? '';
	$blurb         = $row['5050_blurb'] ?? '';
	$button        = $row['5050_button'] ?? '';
	$after_content = $row['5050_after_text'] ?? '';
	$link_to       = $row['link_to'] ?? '';
	$reversed      = 'content_first' === $row['5050_orientation'];
	$btn_class     = 'button';

	if ( $link_to === 'image' && ! empty( $pop_image ) && ! empty( $button['url'] ) ) {
		$button['url'] = $pop_image;
		$btn_class     = 'button fifty-image-content-image__pop-link';
	}

	if ( ! empty( $disp_image ) && ! empty( $title ) && ! empty( $blurb ) ) { ?>
		<div class="fifty-image-content__row <?php echo $reversed ? 'reversed' : ''; ?>" >
			<div class="fifty-image-content__first">
				<div class="fifty-image-content__image-wrap">
					<?php obj_display_pop_img( $pop_image, $disp_image ); ?>
				</div>
			</div>
			<div class="fifty-image-content__second">
				<div class="fifty-image-content__title-wrap">
					<h3 class="fifty-image-content__title"><?php echo $title; ?></h3>
				</div>
				<div class="fifty-image-content__blurb-wrap">
					<div class="fifty-image-content__blurb"><?php echo $blurb; ?></div>
				</div>
				<?php if ( ! empty( $button ) ) : ?>
					<div class="fifty-image-content__button-wrap">
						<?php echo objectiv_link_button( $button, $btn_class ); ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $after_content ) ) : ?>
					<div class="basemt2 tac"><?php echo $after_content; ?></div>
				<?php endif; ?>
			</div>
		</div>
		<?php
	}
}


/**
 * Display a "pop up" image
 */
function obj_display_pop_img( $pop_image = null, $disp_img = null, $become_member_text = false ) {
	$mem_page      = get_field( 'become_member_page', 'option' );
	$mem_link_text = 'Become a Member';

	if ( ! rcp_user_can_access( get_current_user_id(), get_the_ID() ) && is_user_logged_in() ) {
		$mem_link_text = 'Upgrade Membership';
	}

	?>
	<?php if ( ! empty( $pop_image ) && ! $become_member_text ) : ?>
		<a href="<?php echo $pop_image; ?>" class="modaal-img fifty-image-content-image__pop-link">
	<?php endif; ?>

	<?php echo $disp_img; ?>

	<?php if ( $become_member_text && ! empty( $mem_page ) ) : ?>
		<p class="fifty-image-content__become-member">
			<a href="<?php echo get_permalink( $mem_page->ID ); ?>"><?php echo esc_html( $mem_link_text ); ?></a> to view.
			<br>
			<span class="already-member">Already a member? <a href="/login/">Login Here</a></span>
		</p>
	<?php endif; ?>

	<?php if ( ! empty( $pop_image && ! $become_member_text ) ) : ?>
		<div class="magnify-wrap">
			<?php obj_svg( 'magnify' ); ?>
		</div>
		</a>
		<?php
	endif;
}
