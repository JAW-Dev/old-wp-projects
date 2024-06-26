<?php
/*
Plugin Name: Kitces Logout Menu Item
Description: Adds a new Log Out Menu item
Version: 0.0.1
Plugin URI: https://www.kitces.com/
Author: Objectiv
*/

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No scripting please...' );
}

/* Add a metabox in admin menu page */
function mk_lol_add_nav_menu_metabox() {
	add_meta_box( 'mk_lol', __( 'Logout', 'mk_lol' ), 'mk_lol_nav_menu_metabox', 'nav-menus', 'side', 'default' );
}
add_action( 'admin_head-nav-menus.php', 'mk_lol_add_nav_menu_metabox' );

/* The metabox code : Awesome code stolen from screenfeed.fr (GregLone) Thank you mate. */
function mk_lol_nav_menu_metabox( $object ) {
	global $nav_menu_selected_id;

	$elems = array(
		// '#mk_lollogin#'    => __( 'Log In', 'mk_lol' ),
		'#mk_lollogout#' => __( 'Log Out', 'mk_lol' ),
		// '#mk_lolloginout#' => __( 'Log In', 'mk_lol' ) . '|' . __( 'Log Out', 'mk_lol' ),
	);

	class mk_lolLogItems {
		public $db_id  = 0;
		public $object = 'mk_lollog';
		public $object_id;
		public $menu_item_parent = 0;
		public $type             = 'custom';
		public $title;
		public $url;
		public $target     = '';
		public $attr_title = '';
		public $classes    = array();
		public $xfn        = '';
	}

	$elems_obj = array();

	foreach ( $elems as $value => $title ) {
		$elems_obj[ $title ]            = new mk_lolLogItems();
		$elems_obj[ $title ]->object_id = esc_attr( $value );
		$elems_obj[ $title ]->title     = esc_attr( $title );
		$elems_obj[ $title ]->url       = esc_attr( $value );
	}

	$walker = new Walker_Nav_Menu_Checklist( array() );

	?>
  <div id="login-links" class="loginlinksdiv">
	<div id="tabs-panel-login-links-all" class="tabs-panel tabs-panel-view-all tabs-panel-active">
	  <ul id="login-linkschecklist" class="list:login-links categorychecklist form-no-clear">
		<?php echo walk_nav_menu_tree( array_map( 'wp_setup_nav_menu_item', $elems_obj ), 0, (object) array( 'walker' => $walker ) ); ?>
	  </ul>
	</div>
	<p class="button-controls">
	  <span class="add-to-menu">
		<input type="submit"<?php disabled( $nav_menu_selected_id, 0 ); ?> class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to Menu', 'mk_lol' ); ?>" name="add-login-links-menu-item" id="submit-login-links" />
		<span class="spinner"></span>
	  </span>
	</p>
  </div>
	<?php
}

/* Modify the "type_label" */
function mk_lol_nav_menu_type_label( $menu_item ) {
	$elems = array( '#mk_lollogin#', '#mk_lollogout#', '#mk_lolloginout#' );
	if ( isset( $menu_item->object, $menu_item->url ) && 'custom' == $menu_item->object && in_array( $menu_item->url, $elems ) ) {
		$menu_item->type_label = __( 'Dynamic Link', 'mk_lol' );
	}

	return $menu_item;
}
add_filter( 'wp_setup_nav_menu_item', 'mk_lol_nav_menu_type_label' );

/* Used to return the correct title for the double login/logout menu item */
function mk_lol_loginout_title( $title ) {
	$titles = explode( '|', $title );

	if ( ! is_user_logged_in() ) {
		return esc_html( isset( $titles[0] ) ? $titles[0] : __( 'Log In', 'mk_lol' ) );
	} else {
		return esc_html( isset( $titles[1] ) ? $titles[1] : __( 'Log Out', 'mk_lol' ) );
	}
}

/* The main code, this replace the #keyword# by the correct links with nonce ect */
function mk_lol_setup_nav_menu_item( $item ) {
	global $pagenow;

	if ( $pagenow != 'nav-menus.php' && ! defined( 'DOING_AJAX' ) && isset( $item->url ) && strstr( $item->url, '#mk_lol' ) != '' ) {
		$login_page_url      = get_option( 'mk_lol_login_page_url', wp_login_url() );
		$logout_redirect_url = get_option( 'mk_lol_logout_redirect_url', home_url() );

		switch ( $item->url ) {
			case '#mk_lollogin#':
				$item->url = $login_page_url;
				break;
			case '#mk_lollogout#':
				$item->url = wp_logout_url( $logout_redirect_url );
				break;
			default: // Should be #mk_lolloginout#
				$item->url   = ( is_user_logged_in() ) ? wp_logout_url( $logout_redirect_url ) : $login_page_url;
				$item->title = mk_lol_loginout_title( $item->title );
		}
	}

	return $item;
}
add_filter( 'wp_setup_nav_menu_item', 'mk_lol_setup_nav_menu_item' );

function mk_lol_login_redirect_override( $redirect_to, $request, $user ) {
	// If the login failed, or if the user is an Admin - let's not override the login redirect
	if ( ! is_a( $user, 'WP_User' ) || user_can( $user, 'manage_options' ) ) {
		return $redirect_to;
	}

	$login_redirect_url = get_option( 'mk_lol_login_redirect_url', home_url() );
	return $login_redirect_url;
}
// add_filter( 'login_redirect', 'mk_lol_login_redirect_override', 11, 3 );

function mk_lol_settings_page() {
	// $login_page_url      = get_option( 'mk_lol_login_page_url', wp_login_url() );
	// $login_redirect_url  = get_option( 'mk_lol_login_redirect_url', home_url() );
	$logout_redirect_url = get_option( 'mk_lol_logout_redirect_url', home_url() );
	?>
	<div class="wrap">
	  <div class="icon32"></div>
	  <h2><?php _e( 'Login or Logout Menu Item - Settings', 'mk_lol' ); ?></h2>
	  <div class="mk_lol_spacer" style="height:25px;"></div>

	  <?php if ( isset( $_GET['mk_lolsaved'] ) ) : ?>
		<div id="message" class="updated notice notice-success is-dismissible below-h2">
		  <p><?php _e( 'Settings saved.', 'mk_lol' ); ?></p>
		</div>
		<div class="mk_lol_spacer" style="height:25px;"></div>
	  <?php endif; ?>

	  <form action="" method="post">
		<!-- <label for="mk_lol_login_page_url"><?php _e( 'Login Page URL', 'mk_lol' ); ?></label><br/>
		<small><?php _e( 'URL where your login page is found.' ); ?></small><br/>
		<input type="text" id="mk_lol_login_page_url" name="mk_lol_login_page_url" value="<?php echo $login_page_url; ?>" style="min-width:250px;width:60%;" /><br/><br/>

		<label for="mk_lol_login_redirect_url"><?php _e( 'Login Redirect URL', 'mk_lol' ); ?></label><br/>
		<small><?php _e( 'URL to redirect a user to after logging in. Note: Some other plugins may override this URL.' ); ?></small><br/>
		<input type="text" id="mk_lol_login_redirect_url" name="mk_lol_login_redirect_url" value="<?php echo $login_redirect_url; ?>" style="min-width:250px;width:60%;" /><br/><br/> -->

		<label for="mk_lol_logout_redirect_url"><?php _e( 'Logout Redirect URL', 'mk_lol' ); ?></label><br/>
		<small><?php _e( 'URL to redirect a user to after logging out. Note: Some other plugins may override this URL.' ); ?></small><br/>
		<input type="text" id="mk_lol_logout_redirect_url" name="mk_lol_logout_redirect_url" value="<?php echo $logout_redirect_url; ?>" style="min-width:250px;width:60%;" /><br/><br/>

		<?php wp_nonce_field( 'mk_lol_nonce' ); ?>
		<input type="submit" id="mk_lol_settings_submit" name="mk_lol_settings_submit" value="<?php _e( 'Save Settings', 'mk_lol' ); ?>" class="button button-primary" />
	  </form>
	</div>
	<?php
}

function mk_lol_setup_menus() {
	add_options_page( 'mk_lol Settings', 'Login or Logout', 'manage_options', 'mk_lol-settings', 'mk_lol_settings_page' );
}
add_action( 'admin_menu', 'mk_lol_setup_menus' );

function mk_lol_save_settings() {
	if ( isset( $_POST['mk_lol_settings_submit'] ) ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			die( 'Cheating eh?' ); }
		check_admin_referer( 'mk_lol_nonce' );

		$login_page_url      = ( isset( $_POST['mk_lol_login_page_url'] ) && ! empty( $_POST['mk_lol_login_page_url'] ) ) ? $_POST['mk_lol_login_page_url'] : wp_login_url();
		$login_redirect_url  = ( isset( $_POST['mk_lol_login_redirect_url'] ) && ! empty( $_POST['mk_lol_login_redirect_url'] ) ) ? $_POST['mk_lol_login_redirect_url'] : home_url();
		$logout_redirect_url = ( isset( $_POST['mk_lol_logout_redirect_url'] ) && ! empty( $_POST['mk_lol_logout_redirect_url'] ) ) ? $_POST['mk_lol_logout_redirect_url'] : home_url();

		update_option( 'mk_lol_login_page_url', esc_url_raw( $login_page_url ) );
		update_option( 'mk_lol_login_redirect_url', esc_url_raw( $login_redirect_url ) );
		update_option( 'mk_lol_logout_redirect_url', esc_url_raw( $logout_redirect_url ) );

		// This is causing security issues with SiteGround - so we'll do it a different way.
		// wp_redirect($_SERVER['REQUEST_URI']."&mk_lolsaved=true");
		// die();
		$_GET['mk_lolsaved'] = true;
	}
}
add_action( 'admin_init', 'mk_lol_save_settings' );
