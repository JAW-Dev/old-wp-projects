<?php

/**
 * Footer
 *
 */
function objectiv_footer() {
	$footer_contact = get_field( 'footer_contact_info', 'option' );

	?>
	<?php if( ! empty( $footer_contact ) ) : ?>
		<div class="footer-contact">
			<?php echo $footer_contact ?>
		</div>
	<?php endif; ?>
	<div class="footer-creds">
		<div class="footer-left">
			<div>Copyright &copy; <?php echo date( 'Y' ); ?> fpPathfinder, All rights reserved.</div>
		</div>
		<div class="footer-right">
		</div>
	</div>
	<?php
}
