<?php

namespace FP_Core\Integrations\Active_Campaign;

class Tracking_Pixel {
	public function __construct() {}

	static public function init() {
		if ( defined( 'OBJECTIV_DEV_SITE' ) && OBJECTIV_DEV_SITE ) {
			return;
		}
		add_action( 'wp_footer', __CLASS__ . '::output_tracking_pixel' );
	}

	static public function output_tracking_pixel() {
		if ( ! is_user_logged_in() ) {
			return;
		}

		$email = get_userdata( get_current_user_id() )->user_email;
		?>
		<script type="text/javascript">
			(function(e,t,o,n,p,r,i){e.visitorGlobalObjectAlias=n;e[e.visitorGlobalObjectAlias]=e[e.visitorGlobalObjectAlias]||function(){(e[e.visitorGlobalObjectAlias].q=e[e.visitorGlobalObjectAlias].q||[]).push(arguments)};e[e.visitorGlobalObjectAlias].l=(new Date).getTime();r=t.createElement("script");r.src=o;r.async=true;i=t.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)})(window,document,"https://diffuser-cdn.app-us1.com/diffuser/diffuser.js","vgo");
			vgo('setAccount', '252633429');
			vgo('setTrackByDefault', true);
			<?php if ( ! empty( $email ) ) : ?>
			vgo('setEmail', "<?php echo $email; ?>");
			<?php endif; ?>
			vgo('process');
		</script>
		<?php
	}
}
