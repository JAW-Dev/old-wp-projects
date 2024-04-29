<?php

/**
 * FP Member Section Search
 *
 * Output the search section of the member section
 *
 * @return void
 */
function fp_member_section_search() {
	?>
	<div class="member-section-search-outer">
		<div class="wrap">
			<div class="member-section-search">
				<?php
				echo facetwp_display( 'facet', 'resource_category' );
				echo facetwp_display( 'facet', 'resource_type' );
				echo facetwp_display( 'facet', 'search' );
				?>
			</div>
		</div>
	</div>
	<?php
}

/**
 * FP Get View Toggle Button Icon
 *
 * output the icon for the button that toggles the view in the member section between list and categories.
 */
function fp_get_view_toggle_button_icon() {
	?>
	<svg width="17" height="14" viewBox="0 0 17 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M13.3 5.83333H6.53333C5.88933 5.83333 5.83333 6.35483 5.83333 7C5.83333 7.64517 5.88933 8.16667 6.53333 8.16667H13.3C13.944 8.16667 14 7.64517 14 7C14 6.35483 13.944 5.83333 13.3 5.83333ZM15.6333 11.6667H6.53333C5.88933 11.6667 5.83333 12.1882 5.83333 12.8333C5.83333 13.4785 5.88933 14 6.53333 14H15.6333C16.2773 14 16.3333 13.4785 16.3333 12.8333C16.3333 12.1882 16.2773 11.6667 15.6333 11.6667ZM6.53333 2.33333H15.6333C16.2773 2.33333 16.3333 1.81183 16.3333 1.16667C16.3333 0.5215 16.2773 0 15.6333 0H6.53333C5.88933 0 5.83333 0.5215 5.83333 1.16667C5.83333 1.81183 5.88933 2.33333 6.53333 2.33333ZM2.8 5.83333H0.7C0.0559999 5.83333 0 6.35483 0 7C0 7.64517 0.0559999 8.16667 0.7 8.16667H2.8C3.444 8.16667 3.5 7.64517 3.5 7C3.5 6.35483 3.444 5.83333 2.8 5.83333ZM2.8 11.6667H0.7C0.0559999 11.6667 0 12.1882 0 12.8333C0 13.4785 0.0559999 14 0.7 14H2.8C3.444 14 3.5 13.4785 3.5 12.8333C3.5 12.1882 3.444 11.6667 2.8 11.6667ZM2.8 0H0.7C0.0559999 0 0 0.5215 0 1.16667C0 1.81183 0.0559999 2.33333 0.7 2.33333H2.8C3.444 2.33333 3.5 1.81183 3.5 1.16667C3.5 0.5215 3.444 0 2.8 0Z" fill="#293D52"/></svg>
	<?php
}
