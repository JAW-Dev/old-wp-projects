<?php

function objectiv_do_faq_accordion_section( $title = null, $rows = null, $bg_color = 'bg-light-gray' ) { ?>
	<?php if ( ! empty( $rows ) ) : ?>
		<section class="page-section mem-faq-section spt spb <?php echo $bg_color; ?>" id="faq-accordions">
			<?php if ( ! empty( $title ) ) : ?>
			<header class="page-section-header tac mb1">
				<div class="wrap">
					<h2 class="page-section-title f36 border0 tc-text-gray fwb"><?php echo $title; ?></h2>
				</div>
			</header>
			<?php endif; ?>

			<div class="wrap">
				<?php echo obj_do_faq_accordion_list( $rows ); ?>
			</div>
		</section>
		<?php
	endif;
}

function obj_do_faq_accordion_list( $rows ) {
	?>
	<?php if ( ! empty( $rows ) ) : ?>
		<div class="accordion-rows-wrap accordion-block">
			<?php foreach ( $rows as $r ) : ?>
				<?php
				$title   = mk_key_value( $r, 'accordion_title' );
				$icon    = mk_key_value( $r, 'accordion_icon' );
				$content = mk_key_value( $r, 'accordion_content' );
				if ( $icon ) {
					if ( is_array( $icon ) ) {
						$icon_url = mk_key_value( $icon, 'url' );
					} else {
						$icon_url = wp_get_attachment_image_url( $icon, 'small-square' );
					}
				} else {
					$icon_url = null;
				}
				?>
				<?php obj_accordion_row( $title, $icon_url, $content ); ?>
			<?php endforeach; ?>
		</div>
		<?php
endif;
}
