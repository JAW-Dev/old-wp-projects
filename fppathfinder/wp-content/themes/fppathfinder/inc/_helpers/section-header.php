<?php

/**
 * Function obj_section_header takes a $title
 * and displays it as an h2 within a header element
 */

function obj_section_header( $title = null, $class = null, $blurb = null ) {
	if ( ! empty( $title ) ) : ?>
		<header class="section-header <?php echo $class; ?>">
			<h2 class="section-title"><?php echo $title; ?></h2>
			<?php if ( ! empty( $blurb ) ) : ?>
				<div class=" lmb0 fmt0"> <?php echo wpautop( $blurb ); ?></div>
			<?php endif; ?>
		</header>
		<?php
	endif;
}
