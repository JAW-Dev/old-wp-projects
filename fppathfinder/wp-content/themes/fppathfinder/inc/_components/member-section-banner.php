<?php

/**
 * FP Member Section Banner
 *
 * Output the member section banner.
 *
 * @return void
 */
function fp_member_section_banner() {
	?>
	<div class="member-section-banner">
		<div class="wrap">
			<div class="member-section-banner-inner">
				<div class="title">Member Section</div>
				<div class="sidebar"><?php dynamic_sidebar( 'members-area-sidebar' ); ?></div>
			</div>
		</div>
	</div>
	<?php
}
