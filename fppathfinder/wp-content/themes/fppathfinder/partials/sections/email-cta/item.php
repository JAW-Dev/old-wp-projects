<section class="email-cta-section bg-img page-flexible-section <?php echo $padding_classes; ?>" style="background-image: url(<?php echo $bg_img['sizes']['large'] ?>)">
	<div class="bg-overlay"<?php echo $overlay_styles; ?>></div>
	<div class="wrap">
		<?php if ( 'one' === $columns ) : ?>
			<div class="email-cta__inner">
				<?php main_content( $title, $blurb, $form ); ?>
			</div>
		<?php elseif ( 'two' === $columns ) : ?>
			<div class="email-cta__inner-two">
				<div class="inner-two__content">
					<?php main_content( $title, $blurb, $form ); ?>
				</div>
				<div class="inner-two__image">
					<img src="<?php echo esc_url( $second_column_image ); ?>" />
				</div>
			</div>
		<?php endif; ?>
	</div>
</section>
<?php

function main_content( $title, $blurb, $form ) {
	if ( ! empty( $title ) ) :
		?>
		<h1 class="email-cta__title"><?php echo $title ?></h1>
		<?php
	endif;

	if( ! empty( $blurb ) ) :
		?>
		<div class="email-cta__blurb"><?php echo $blurb ?></div>
		<?php
	endif;

	if( ! empty( $form ) ) :
		?>
		<div class="email-cta__form">
			<?php echo $form ?>
		</div>
		<?php
	endif;
}
