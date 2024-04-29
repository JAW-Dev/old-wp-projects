<?php

/*
Template Name: CFP CE Credit
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'get-cfp-ce-credit';
	return $classes;

}

// full width layout
add_filter ( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action( 'genesis_after_entry_content', 'cgd_cfp_ce_boxes' );
function cgd_cfp_ce_boxes() { ?>
    <?php
	$prefix = '_cgd_';
	$p_title = get_post_meta( get_the_ID(), $prefix . 'purchase_credits_box_title', true );
	$p_desc = get_post_meta( get_the_ID(), $prefix . 'purchase_credits_box_desc', true );
	$p_link = get_post_meta( get_the_ID(), $prefix . 'purchase_credits_box_link', true );
	$p_text = get_post_meta( get_the_ID(), $prefix . 'purchase_credits_box_button_text', true );
	$m_title = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_box_title', true );
	$m_desc = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_box_desc', true );
	$m_link = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_box_link', true );
	$m_text = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_box_button_text', true );
	$al_btitle = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_already_box_title', true );
	$al_blink = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_already_box_link', true );
	$al_bbtext = get_post_meta( get_the_ID(), $prefix . 'ce_become_member_already_box_button_text', true );

	?>

	<div class="cfp-ce-boxes">
		<div class="cfp-ce-box one-half highlight-box first">
			<h3 class="cfp-ce-box-title"><?php echo $p_title; ?></h3>
			<div class="cfp-ce-box-desc">
				<?php echo wpautop( $p_desc ); ?>
			</div>
			<a href="<?php echo $p_link; ?>" class="button"><?php echo $p_text; ?></a>
		</div>
		<div class="cfp-ce-box one-half">
			<h3 class="cfp-ce-box-title"><?php echo $m_title; ?></h3>
			<div class="cfp-ce-box-desc">
				<?php echo wpautop( $m_desc ); ?>
			</div>
			<a href="<?php echo $m_link; ?>" class="button"><?php echo $m_text; ?></a>
		</div>
	</div>
	<?php if ( ! empty( $al_blink ) && ! empty( $al_bbtext ) ) : ?>
		<div class="cfp-ce-box full-width-box">
			<?php if ( ! empty( $al_btitle ) ) : ?>
				<h3 class="cfp-ce-box-title"><?php echo $al_btitle ?></h3>
			<?php endif; ?>
			<a href="<?php echo $al_blink ?>" class="button"><?php echo $al_bbtext ?></a>
		</div>
	<?php endif; ?>
<?php }



genesis();
