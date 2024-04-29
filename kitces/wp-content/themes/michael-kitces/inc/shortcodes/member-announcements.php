<?php

function mk_member_announcements_sidebar_shortcode( $atts ) {
	ob_start();
	if ( is_active_sidebar( 'member_announcements_sidebar' ) ) {
		?>
			<div class="member-announcements-wrap">
				<?php dynamic_sidebar( 'member_announcements_sidebar' ); ?>
			</div>
		<?php
	}
	return ob_get_clean();
}
add_shortcode( 'member-announcements-sidebar', 'mk_member_announcements_sidebar_shortcode' );
