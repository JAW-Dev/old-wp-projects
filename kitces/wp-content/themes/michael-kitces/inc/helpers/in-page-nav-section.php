<?php

function mk_do_in_page_nav_section( $nav_items = null, $sec_class = null, $link_class = null ) {
	if ( ! empty( $nav_items ) && is_array( $nav_items ) ) {
		?>
		<section class="in-page-nav-section <?php echo $sec_class; ?>">
			<div class="wrap">
				<div class="in-page-nav-inner">
					<?php foreach ( $nav_items as $nav_item ) : ?>
						<?php echo mk_link_html( $nav_item['link'], $link_class ); ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
		<?php
	}
}
