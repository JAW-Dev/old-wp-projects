<section id="<?php echo esc_attr( $section_id ); ?>" class="accordion-section page-flexible-section <?php echo $padding_classes; ?>">
	<div class="wrap">

		<?php obj_section_header( $title ); ?>

		<?php if ( ! empty( $intro_text ) ) : ?>
			<div class="accordion-intro">
				<?php echo $intro_text; ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $accordions ) ) : ?>
			<?php if ( 1 == $two_columns ) : ?>
			<div class="accordions-columns-wrap one2gridlarge">
				<div class="accordions-wrap-left">
					<?php foreach ( $accordions as $key => $accordion ) : ?>
						<?php if ( $key < $midpoint ) : ?>
							<?php obj_accordion_row( $accordion ); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
				<div class="accordions-wrap-right">
					<?php foreach ( $accordions as $key => $accordion ) : ?>
						<?php if ( $key >= $midpoint ) : ?>
							<?php obj_accordion_row( $accordion ); ?>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
			<?php else : ?>
				<div class="accordions-wrap">
					<?php foreach ( $accordions as $accordion ) : ?>
						<?php obj_accordion_row( $accordion ); ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</section>
