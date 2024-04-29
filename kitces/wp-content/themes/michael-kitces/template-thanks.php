<?php

/*
Template Name: Thanks For Buying
*/

add_filter( 'body_class', 'cgd_body_class' );
function cgd_body_class( $classes ) {

	$classes[] = 'thanks-purchase';
	return $classes;

}

// full width layout
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

remove_action( 'genesis_loop', 'genesis_do_loop' );

add_action( 'genesis_loop', 'cgd_thanks_content' );

function cgd_thanks_content() {
	$conv_product_id = mk_get_url_param( 'product' );
	$conv_price      = mk_get_url_param( 'o_subtotal' );
	$invoice_id      = mk_get_url_param( 'o_id' );
	$customer_email  = mk_get_chargebee_user_email_from_invoice_id( $invoice_id );
	if ( $conv_product_id && $conv_price && ! empty( $customer_email ) ) {
		$conv_price = floatval( $conv_price / 100 );
		mk_fb_track_conversion( $customer_email, $conv_product_id, $conv_price );
	}
	?>

	<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g stroke="#000" stroke-linejoin="round" stroke-miterlimit="10" fill="none"><circle stroke="#D4AF37" cx="11.542" cy="16" r="7.5"/><path stoke="#005392" d="M11.542 11.5l1.5 3h3l-2.5 2 1 3.5-3-1.876-3 1.876 1-3.5-2.5-2h3z"/><g stroke-linecap="round"><path d="M16.74 7.47l3.302-6.97h-2.5l-2.862 6.01M18.53 8.855l4.012-8.355h-2.5l-3.302 6.97"/></g><g stroke-linecap="round"><path d="M6.301 7.47l-3.301-6.97h2.5l2.862 6.01M4.51 8.855l-4.01-8.355h2.5l3.301 6.97"/></g></g></svg>

	<h1>Thanks for Your Kitces.com Purchase!</h1>

	<?php
	if ( isset( $_GET['product'] ) && $_GET['product'] == 'kitces-report-autopilot' ) {
		?>

		<div class="h4-wrap">
			<h4>
				Please take a moment to complete the brief survey below.
				<br>
				In the meantime, your login details have also been sent to your email address for future reference!
			</h4>
		</div>

		<div class="thank-you-survey-wrapper">
			<script>(function(e,t,c,o){var s,n,r;e.SMCX=e.SMCX||[],t.getElementById(o)||(s=t.getElementsByTagName(c),n=s[s.length-1],r=t.createElement(c),r.type="text/javascript",r.async=!0,r.id=o,r.src=["https:"===location.protocol?"https://":"http://","widget.surveymonkey.com/collect/website/js/45xoOwHGPIr4XOc7pBD6c6YBUEAskDjLhpdbAV34eGIuUJx93NFCRvkRpE6R1r8e.js"].join(""),n.parentNode.insertBefore(r,n))})(window,document,"script","smcx-sdk");
			</script>
		</div>

		<?php
	} else {
		?>
		<div class="h4-wrap">
			<h4>Please check your email for your login details!</h4>
		</div>
		<a href="<?php echo esc_url( home_url() . '/login/' ); ?>" class="button">Proceed To Login To The Members Section To Access Your Materials</a>
		<?php
	}
}

genesis();
