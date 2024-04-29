<?php
/**
 * Functions
 */

// Composer autoloader
require_once 'vendor/autoload.php';

// Load the layouts
require_once get_stylesheet_directory() . '/inc/_layout/00-load-layouts.php';

// Helper functions
require_once get_stylesheet_directory() . '/inc/_helpers/00-load-helpers.php';

// Load in scripts (enqueue all the things)
require_once get_stylesheet_directory() . '/inc/scripts.php';

// Load in the custom post types
require_once get_stylesheet_directory() . '/inc/_post-types/00-load-cpts.php';

// Load in the custom taxonomies
require_once get_stylesheet_directory() . '/inc/_taxonomies/00-load-taxonomies.php';

// Load in the custom widgets
require_once get_stylesheet_directory() . '/inc/_widgets/00-load-widgets.php';

// Load in components
require_once get_stylesheet_directory() . '/inc/_components/00-load-components.php';

/**
 * Theme Setup
 *
 * This setup function attaches all of the site-wide functions
 * to the correct hooks and filters. All the functions themselves
 * are defined below this setup function.
 */

 // Crop Images
if ( false === get_option( 'medium_crop' ) ) {
	add_option( 'medium_crop', '1' );
} else {
	update_option( 'medium_crop', '1' );
}

add_action( 'genesis_setup', 'child_theme_setup', 15 );
function child_theme_setup() {

	define( 'CHILD_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );

	// ** Backend **
	// Image Sizes
	add_image_size( 'obj_post_thumb', 250, 180, true );
	add_image_size( 'obj_lsquare', 400, 400, true );
	add_image_size( 'obj_fifty_cont', 1000, 810, true );

	// Structural Wraps
	add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );

	// Menus
	add_theme_support(
		'genesis-menus',
		array(
			'primary'     => 'Primary Navigation Menu',
			'mobile-menu' => 'Mobile Navigation Menu',
		)
	);

	// * Reposition the primary navigation menu
	remove_action( 'genesis_after_header', 'genesis_do_nav' );
	add_action( 'genesis_header_right', 'genesis_do_nav' );

	// * Add HTML5 markup structure
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

	// Sidebars
	// unregister_sidebar( 'sidebar-alt' );
	genesis_register_sidebar(
		array(
			'name' => 'Member Area Sidebar',
			'id'   => 'members-area-sidebar',
		)
	);

	add_theme_support( 'genesis-footer-widgets', 3 );

	// Remove Unused Page Layouts
	genesis_unregister_layout( 'content-sidebar-sidebar' );
	genesis_unregister_layout( 'sidebar-sidebar-content' );
	genesis_unregister_layout( 'sidebar-content-sidebar' );

	// Remove Unused User Settings
	add_filter( 'user_contactmethods', 'objectiv_contactmethods' );
	add_action( 'admin_init', 'objectiv_remove_user_settings' );

	// Editor Styles
	// add_editor_style( 'editor-style.css' );
	// Reposition Genesis Metaboxes
	remove_action( 'admin_menu', 'genesis_add_inpost_seo_box' );
	// add_action( 'admin_menu', 'objectiv_add_inpost_seo_box' );
	remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );
	// add_action( 'admin_menu', 'objectiv_add_inpost_layout_box' );
	// Remove Genesis Widgets
	add_action( 'widgets_init', 'objectiv_remove_genesis_widgets', 20 );

	// Remove Genesis Theme Settings Metaboxes
	add_action( 'genesis_theme_settings_metaboxes', 'objectiv_remove_genesis_metaboxes' );

	// Don't update theme
	add_filter( 'http_request_args', 'objectiv_dont_update_theme', 5, 2 );

	// ** Frontend **
	// Remove Edit link
	add_filter( 'genesis_edit_post_link', '__return_false' );

	// Responsive Meta Tag
	add_action( 'genesis_meta', 'objectiv_viewport_meta_tag' );

	// Footer
	remove_action( 'genesis_footer', 'genesis_do_footer' );
	add_action( 'genesis_footer', 'objectiv_footer' );

	// Register Widgets
	add_action(
		'widgets_init',
		function() {
			register_widget( 'OBJ_MailChimp_Widget' );
			register_widget( 'OBJ_CTA_Widget' );
			register_widget( 'OBJ_Download_Cats_Widget' );
			register_widget( 'OBJ_Download_Filter_Widget' );
			register_widget( 'OBJ_Member_Welcome_Widget' );
		}
	);

	// Remove Blog & Archive Template From Genesis
	add_filter( 'theme_page_templates', 'bourncreative_remove_page_templates' );
	function bourncreative_remove_page_templates( $templates ) {
		unset( $templates['page_blog.php'] );
		unset( $templates['page_archive.php'] );
		return $templates;
	}

}

// ** Backend Functions ** //

/**
 * Customize Contact Methods
 *
 * @since 1.0.0
 *
 * @author Bill Erickson
 * @link http://sillybean.net/2010/01/creating-a-user-directory-part-1-changing-user-contact-fields/
 *
 * @param array $contactmethods
 * @return array
 */
function objectiv_contactmethods( $contactmethods ) {
	unset( $contactmethods['aim'] );
	unset( $contactmethods['yim'] );
	unset( $contactmethods['jabber'] );

	return $contactmethods;
}

/**
 * Remove Use Theme Settings
 */
function objectiv_remove_user_settings() {
	remove_action( 'show_user_profile', 'genesis_user_options_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_options_fields' );
	remove_action( 'show_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_archive_fields' );
	remove_action( 'show_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_seo_fields' );
	remove_action( 'show_user_profile', 'genesis_user_layout_fields' );
	remove_action( 'edit_user_profile', 'genesis_user_layout_fields' );
}

/**
 * Register a new meta box to the post / page edit screen, so that the user can
 * set SEO options on a per-post or per-page basis.
 *
 * @category Genesis
 * @package Admin
 * @subpackage Inpost-Metaboxes
 *
 * @since 0.1.3
 *
 * @see genesis_inpost_seo_box() Generates the content in the meta box
 */
function objectiv_add_inpost_seo_box() {

	if ( genesis_detect_seo_plugins() ) {
		return;
	}

	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-seo' ) ) {
			add_meta_box( 'genesis_inpost_seo_box', __( 'Theme SEO Settings', 'genesis' ), 'genesis_inpost_seo_box', $type, 'normal', 'default' );
		}
	}

}

/**
 * Register a new meta box to the post / page edit screen, so that the user can
 * set layout options on a per-post or per-page basis.
 *
 * @category Genesis
 * @package Admin
 * @subpackage Inpost-Metaboxes
 *
 * @since 0.2.2
 *
 * @see genesis_inpost_layout_box() Generates the content in the boxes
 *
 * @return null Returns null if Genesis layouts are not supported
 */
function objectiv_add_inpost_layout_box() {

	if ( ! current_theme_supports( 'genesis-inpost-layouts' ) ) {
		return;
	}

	foreach ( (array) get_post_types( array( 'public' => true ) ) as $type ) {
		if ( post_type_supports( $type, 'genesis-layouts' ) ) {
			add_meta_box( 'genesis_inpost_layout_box', __( 'Layout Settings', 'genesis' ), 'genesis_inpost_layout_box', $type, 'normal', 'default' );
		}
	}

}

/**
 * Remove Genesis widgets
 *
 * @since 1.0.0
 */
function objectiv_remove_genesis_widgets() {
	unregister_widget( 'Genesis_eNews_Updates' );
	unregister_widget( 'Genesis_Featured_Page' );
	unregister_widget( 'Genesis_Featured_Post' );
	unregister_widget( 'Genesis_Latest_Tweets_Widget' );
	unregister_widget( 'Genesis_User_Profile_Widget' );
}

/**
 * Remove Genesis Theme Settings Metaboxes
 *
 * @since 1.0.0
 * @param string $_genesis_theme_settings_pagehook
 */
function objectiv_remove_genesis_metaboxes( $_genesis_theme_settings_pagehook ) {
	// remove_meta_box( 'genesis-theme-settings-feeds',      $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-header',     $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-nav', $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-layout',    $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-breadcrumb', $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-comments',   $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-posts',      $_genesis_theme_settings_pagehook, 'main' );
	remove_meta_box( 'genesis-theme-settings-blogpage', $_genesis_theme_settings_pagehook, 'main' );
	// remove_meta_box( 'genesis-theme-settings-scripts',    $_genesis_theme_settings_pagehook, 'main' );
}

/**
 * Don't Update Theme
 *
 * @since 1.0.0
 *
 * If there is a theme in the repo with the same name,
 * this prevents WP from prompting an update.
 *
 * @author Mark Jaquith
 * @link http://markjaquith.wordpress.com/2009/12/14/excluding-your-plugin-or-theme-from-update-checks/
 *
 * @param array  $r, request arguments
 * @param string $url, request url
 * @return array request arguments
 */

function objectiv_dont_update_theme( $r, $url ) {
	if ( 0 !== strpos( $url, 'http://api.wordpress.org/themes/update-check' ) ) {
		return $r; // Not a theme update request. Bail immediately.
	}
	$themes = unserialize( $r['body']['themes'] );
	unset( $themes[ get_option( 'template' ) ] );
	unset( $themes[ get_option( 'stylesheet' ) ] );
	$r['body']['themes'] = serialize( $themes );
	return $r;
}

// ** Frontend Functions ** //
// * Display a custom favicon
add_filter( 'genesis_pre_load_favicon', 'objectiv_favicon_filter' );
function objectiv_favicon_filter( $favicon_url ) {
	return '';
}

// Email obfuscation
function objectiv_hide_email( $email ) {

	$character_set = '+-.0123456789@ABCDEFGHIJKLMNOPQRSTUVWXYZ_abcdefghijklmnopqrstuvwxyz';
	$key           = str_shuffle( $character_set );
	$cipher_text   = '';
	$id            = 'e' . rand( 1, 999999999 );
	for ( $i = 0;$i < strlen( $email );
	$i += 1 ) {
		$cipher_text .= $key[ strpos( $character_set, $email[ $i ] ) ];
	}
	$script  = 'var a="' . $key . '";var b=a.split("").sort().join("");var c="' . $cipher_text . '";var d="";';
	$script .= 'for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));';
	$script .= 'document.getElementById("' . $id . '").innerHTML="<a href=\\"mailto:"+d+"\\">"+d+"</a>"';
	$script  = 'eval("' . str_replace( array( '\\', '"' ), array( '\\\\', '\"' ), $script ) . '")';
	$script  = '<script type="text/javascript">/*<![CDATA[*/' . $script . '/*]]>*/</script>';

	return '<span id="' . $id . '">[javascript protected email address]</span>' . $script;

}

/** * Remove editor menu
 */
function remove_editor_menu() {
	remove_action( 'admin_menu', '_add_themes_utility_last', 101 );
}
add_action( '_admin_menu', 'remove_editor_menu', 1 );


/**
 * Hide editor for content builder pages.
 */
function objectiv_hide_editor() {

	$get_post   = ( isset( $_GET['post'] ) ) ? $_GET['post'] : '';
	$get_postid = ( isset( $_POST['post_ID'] ) ) ? $_POST['post_ID'] : '';

	// Get the Post ID.
	$post_id = $get_post ? $get_post : $get_postid;
	if ( ! isset( $post_id ) ) {
		return;
	}

	// Get the name of the Page Template file.
	$template_file = get_post_meta( $post_id, '_wp_page_template', true );

	$hide_templates = array(
		'template-content-builder.php',
		'template-log-in.php',
		'template-about.php',
		'template-become-member.php',
		'template-lead-gen.php',
		'template-lead-thanks.php',
		'template-registration-welcome.php',
		'template-license-agreement.php',
		'template-getting-started.php',
	);

	if ( in_array( $template_file, $hide_templates ) ) { // edit the template name
		remove_post_type_support( 'page', 'editor' );
	}
}
add_action( 'admin_init', 'objectiv_hide_editor' );

// Dequeue woo cart scripts
add_action( 'wp_enqueue_scripts', 'dequeue_woocommerce_cart_fragments', 11 );
function dequeue_woocommerce_cart_fragments() {
	if ( is_front_page() || is_single() ) {
		wp_dequeue_script( 'wc-cart-fragments' );
	}
}


add_action( 'genesis_before_header', 'objectiv_ie_alert' );
function objectiv_ie_alert() {
	?>
	<!--[if IE]>
	 <div class="alert alert-warning">
		<?php _e( 'You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/?locale=en">upgrade your browser</a> to improve your experience.', 'sage' ); ?>
	 </div>
   <![endif]-->
	<?php
}

/*
 * Modify TinyMCE editor to remove H1 & Pre.
 */
add_filter( 'tiny_mce_before_init', 'tiny_mce_remove_unused_formats' );
function tiny_mce_remove_unused_formats( $init ) {
	// Add block format elements you want to show in dropdown
	$init['block_formats'] = 'Paragraph=p;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;';
	return $init;
}

add_filter( 'the_generator', '__return_empty_string' );

function objectiv_get_short_description( $post_id = null, $length = null ) {

	$excerpt   = get_the_excerpt( $post_id );
	$post_type = get_post_type( $post_id );

	$excerpt_length = 55;
	if ( ! empty( $length ) ) {
		$excerpt_length = $length;
	}

	if ( empty( $excerpt ) || $post_type === 'download' ) {

		if ( $post_type === 'download' ) {
			$content = strip_shortcodes( get_field( 'download_description', $post_id ) );
		}

		if ( empty( $content ) ) {
			$content = get_post_meta( $post_id, 'page_flexible_sections_0_content', true );
		}
		if ( empty( $content ) ) {
			$post    = get_post( $post_id );
			$content = strip_shortcodes( $post->post_content );
		}
		$excerpt = $content;
	}

	$excerpt = wp_trim_words( $excerpt, $excerpt_length );

	return $excerpt;
}

// Decide the banner image
function decide_banner_bg_img() {
	$thumbnail_id  = get_post_thumbnail_id();
	$thumbnail_url = wp_get_attachment_image_url( $thumbnail_id, 'large' );

	// Remove thumnail url if on archive/blog/tax
	if ( is_home() || is_archive() || is_tax() ) {
		$thumbnail_url = null;
	}

	// If we are on an archive or post type lets use that defualt image
	if ( is_home() || is_singular( 'post' ) ) {
		$default_bg_image_id = get_field( 'pbs_default_banner_image', 'options' );
		if ( is_array( $default_bg_image_id ) && array_key_exists( 'ID', $default_bg_image_id ) ) {
			$bg_image_url = wp_get_attachment_image_url( $default_bg_image_id['ID'], 'large' );
		} else {
			$bg_image_url = '';
		}
	}

	// If default img not empty use it, if its empty use the base default
	if ( empty( $bg_image_url ) ) {
		$default_bg_image_id = get_field( 'default_banner_image', 'options' );
		$bg_image_url        = ! empty( $default_bg_image_id['ID'] ) ? wp_get_attachment_image_url( $default_bg_image_id['ID'], 'large' ) : '';
	}

	if ( ! empty( $thumbnail_url ) ) {
		$bg_image_url = $thumbnail_url;
	}

	return $bg_image_url;
}

// Decide titles for banners
function decide_banner_title() {

	$title = null;

	if ( is_home() || is_singular( 'post' ) ) {
		$title = 'Blog';
	} elseif ( is_category() || is_date() ) {
		$title = get_the_archive_title();
	} elseif ( is_tax( 'download-cat' ) ) {
		$term = get_queried_object()->term_id;
		$term = get_term( $term );
		if ( ! empty( $term ) ) {
			$title = $term->name;
		} else {
			$title = 'Member Section';
		}
	} elseif ( is_post_type_archive( 'download' ) ) {
		$title = 'Member Section';
	} elseif ( is_post_type_archive( 'download-bundle' ) ) {
		$title = 'One Click Downloads';
	} elseif ( is_search() ) {
		$title = 'Results for: ' . get_search_query();
	} elseif ( is_404() ) {
		$title = 'This page returned a 404';
	} else {
		$title = get_the_title();
	}

	return $title;
}

// Pretty Dump of Variables
function ovdump( $data ) {
	print( '<pre>' . print_r( $data, true ) . '</pre>' );
}

add_filter( 'excerpt_more', 'objectiv_get_read_more_link' );
function objectiv_get_read_more_link() {
	return '';
}

function obj_id_from_string( $string = null, $rand = true ) {
	if ( ! empty( $string ) ) {
		$whoa = substr( md5( microtime() ), rand( 0, 26 ), 5 );
		if ( $rand ) {
			return strtolower( preg_replace( '/\PL/u', '', $string ) . $whoa );
		} else {
			return strtolower( preg_replace( '/\PL/u', '', $string ) );
		}
	} else {
		return null;
	}
}



/**
 * Show all Download items on archive
 */

add_action( 'pre_get_posts', 'obj_show_all_downloads' );

function obj_show_all_downloads( $query ) {

	if ( ! is_admin() && $query->is_main_query() ) {

		if ( is_post_type_archive( 'download' ) || is_tax( 'download-cat' ) ) {

			$query->set( 'posts_per_page', -1 );

		}
	}
}

// Footer cta
add_action( 'genesis_before_footer', 'do_footer_cta_logic' );
function do_footer_cta_logic() {
	$ctas    = get_field( 'footer_cta', 'option' );
	$current = get_the_ID();

	if ( ! empty( $ctas ) ) {
		foreach ( $ctas as $cta ) {
			$display_setting      = obj_key_value( $cta, 'display_settings' );
			$logged_out_only      = obj_key_value( $cta, 'non_logged_in_users_only' );
			$hide_single_download = obj_key_value( $cta, 'remove_on_download_single' );
			$specific_pages       = obj_key_value( $cta, 'specific_pages' );

			$cta_options = array(
				'cta_type'                          => obj_key_value( $cta, 'cta_type' ),
				'background'                        => obj_key_value( $cta, 'background' ),
				'bg_img'                            => obj_key_value( $cta, 'background_image' ),
				'title'                             => obj_key_value( $cta, 'title' ),
				'blurb'                             => obj_key_value( $cta, 'blurb' ),
				'display_testimonials'              => obj_key_value( $cta, 'display_testimonials' ),
				'testimonials_to_display'           => obj_key_value( $cta, 'testimonials_to_display' ),
				'number_of_testimonials_to_display' => obj_key_value( $cta, 'number_of_testimonials_to_display' ),
				'specific_testimonials'             => obj_key_value( $cta, 'specific_testimonials' ),
				'button'                            => obj_key_value( $cta, 'button' ),
				'show_sample_button'                => obj_key_value( $cta, 'show_sample_button' ),
			);

			$display = false;
			if ( $display_setting === 'all' ) {
				$display = true;
			} elseif ( $display_setting === 'disable' ) {
				$display = false;
			} elseif ( $display_setting === 'specific' && is_array( $specific_pages ) ) {
				if ( in_array( $current, $specific_pages ) ) {
					$display = true;
				}
			} elseif ( $display_setting === 'all-but' && is_array( $specific_pages ) ) {
				if ( ! in_array( $current, $specific_pages ) ) {
					$display = true;
				}

				if ( $hide_single_download && ( is_singular( 'download' ) || is_singular( 'resource' ) ) ) {
					$display = false;
				}
			} elseif ( $display_setting === 'single-download' && ( is_singular( 'download' ) || is_singular( 'resource' ) ) ) {
				$display = true;
			} elseif ( $display_setting === 'single-checklist' && is_singular( 'checklist' ) ) {
				$display = true;
			}

			if ( $logged_out_only && is_user_logged_in() ) {
				$display = false;
			}

			if ( is_post_type_archive( 'resource' ) ) {
				$display = false;
			}

			if ( $display ) {
				do_footer_cta( $cta_options );
			}
		}
	}
}

// function add_upload_cap() {
// $role            = get_role( 'contributor' );
// $capability_type = 'download';

// $role->add_cap( "edit_{$capability_type}" );
// $role->add_cap( "delete_{$capability_type}" );
// $role->add_cap( "read_{$capability_type}" );
// $role->add_cap( $capability_type );
// $role->add_cap( 'upload_files' );
// }
// add_action( 'admin_init', 'add_upload_cap' );

function add_profitwell_script_to_footer() {
	$token = 'd7d4e78ca3dc344ebd0f9a7abcda1777';

	$start_options = '{}';
	$current_user  = wp_get_current_user();
	if ( $current_user->exists() ) {
		$start_options = "{user_email: '{$current_user->user_email}'}";
	}

	echo "
    <script id='profitwell-js' data-pw-auth='$token'>
        (function(i,s,o,g,r,a,m){i[o]=i[o]||function(){(i[o].q=i[o].q||[]).push(arguments)};
        a=s.createElement(g);m=s.getElementsByTagName(g)[0];a.async=1;a.src=r+'?auth='+
        s.getElementById(o+'-js').getAttribute('data-pw-auth');m.parentNode.insertBefore(a,m);
        })(window,document,'profitwell','script','https://public.profitwell.com/js/profitwell.js');

        profitwell('start', $start_options);
    </script>
    ";
}
add_action( 'wp_footer', 'add_profitwell_script_to_footer' );

add_action( 'rcp_subscription_details_bottom', 'fp_your_membership_faq_section' );
function fp_your_membership_faq_section() {

	if ( ! is_page( 396 ) ) {
		return;
	}

	$details         = get_field( 'your_membership_page_frequently_asked_questions', 'option' );
	$title           = $details['section_title'];
	$intro_text      = $details['intro_text'];
	$two_columns     = $details['two_columns'];
	$accordions      = $details['accordion_repeater'];
	$midpoint        = ! empty( $accordions ) ? count( $accordions ) / 2 : null;
	$sufficient_info = ! empty( $accordions ) && ! empty( $title );

	if ( $sufficient_info ) {
		?>
		<section class="accordion-section page-flexible-section" style="margin-top: 6rem;">
			<div class="wrap">

				<?php obj_section_header( $title ); ?>

				<?php if ( ! empty( $intro_text ) ) : ?>
					<div class="accordion-intro">
						<?php echo $intro_text; ?>
					</div>
				<?php endif; ?>

				<?php if ( $two_columns ) : ?>
					<div class="accordions-columns-wrap one2gridlarge">
						<div class="accordions-wrap-left">
							<?php foreach ( $accordions as $key => $accordion ) : ?>
								<?php if ( $key < $midpoint ) : ?>
									<?php obj_accordion_row( $accordion ); ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<div class="accordions-wrap-right">
							<?php foreach ( $accordions as $key => $accordion ) : ?>
								<?php if ( $key >= $midpoint ) : ?>
									<?php obj_accordion_row( $accordion ); ?>
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
					</div>
				<?php else : ?>
					<div class="accordions-wrap">
						<?php foreach ( $accordions as $accordion ) : ?>
							<?php obj_accordion_row( $accordion ); ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
}

/**
 * Adds the custom fields to the member edit screen
 *
 * @param int $user_id The user ID.
 */
function company_member_edit_field( $user_id = 0 ) {

	$company = get_user_meta( $user_id, 'company_name', true );
	?>
	?>
	<tr valign="top">
		<th scope="row" valign="top">
			<label for="company_name">Company</label>
		</th>
		<td>
			<input name="company_name" id="company_name" type="text" value="<?php echo esc_attr( $company ); ?>"/>
		</td>
	</tr>
	<?php
}
add_action( 'rcp_edit_member_after', 'company_member_edit_field' );

/**
 * Stores the information submitted durring profile update
 *
 * @param int $user_id The user ID.
 */
function pw_rcp_save_user_fields_on_profile_save( $user_id ) {
	if ( isset( $_POST['company_name'] ) ) {
		update_user_meta( $user_id, 'company_name', sanitize_text_field( $_POST['company_name'] ) );
	}
}
add_action( 'rcp_user_profile_updated', 'pw_rcp_save_user_fields_on_profile_save', 10 );
add_action( 'rcp_edit_member', 'pw_rcp_save_user_fields_on_profile_save', 10 );

/**
 * Add license agreement user meta
 *
 * @param array $entry The form entry data.
 * @param array $form  The Form data.
 */
function license_agreeement_user_meta_save( $entry, $form ) {
	if ( 'License Agreement' === $form['title'] ) {
		$user_id = $entry[2];
		$meta    = get_user_meta( $user_id, 'rcp_privacy_policy_agreed', true );

		if ( ! $meta ) {
			update_user_meta( $user_id, 'rcp_privacy_policy_agreed', 1 );
		}
	}
}

add_action( 'gform_after_submission', 'license_agreeement_user_meta_save', 10, 2 );

/**
 * Redirect user if they have not agreed to license.
 */
function redirect_user_no_license_agree() {
	global $rcp_options;

	$current_path             = $_SERVER['REQUEST_URI'];
	$license_page_url         = get_field( 'fp_license_agreement_page_url', 'option' );
	$license_page_path        = $license_page_url ? parse_url( $license_page_url )['path'] : false;
	$already_on_license_page  = $current_path === $license_page_path;
	$has_active_membership    = rcp_user_has_active_membership();
	$user_already_agreed      = get_user_meta( get_current_user_id(), 'rcp_privacy_policy_agreed', true );
	$user_is_trying_to_logout = isset( $_REQUEST['action'] ) && 'logout' === $_REQUEST['action'];
	$is_rcpga_invite_link     = isset( $_REQUEST['rcpga-invite-key'] );
	$page_id                  = get_the_ID();
	$is_profile_edit          = isset( $rcp_options['edit_profile'] ) && (string) $page_id === $rcp_options['edit_profile'];

	$should_redirect_conditions = array(
		! $is_rcpga_invite_link,
		! $is_profile_edit,
		! is_admin(),
		$has_active_membership,
		! $already_on_license_page,
		! $user_already_agreed,
		$license_page_url != false,
		! $user_is_trying_to_logout,
	);

	$should_redirect = count( array_filter( $should_redirect_conditions ) ) === count( $should_redirect_conditions );

	if ( $should_redirect ) {
		wp_safe_redirect( $license_page_path );
		exit;
	}
}
add_action( 'wp', 'redirect_user_no_license_agree', 0 );

add_filter( 'wp_nav_menu_objects', 'filter_group_settings_menu_item' );
function filter_group_settings_menu_item( $items ) {

	$group_members = function_exists( 'rcpga_get_group_members' ) ? rcpga_get_group_members( array( 'user_id' => get_current_user_id() ) ) : false;

	$is_active_owner = function( $group_member ) {
		$role = rcpga_get_user_group_role( get_current_user_id(), $group_member->get_group_id() );

		return ( 'owner' === $role || 'admin' === $role ) && $group_member->get_group()->is_active();
	};

	$active_group_owners = array_filter( $group_members, $is_active_owner );

	$group_settings_page_url = get_field( 'fp_group_settings_page', 'option' );

	return ! empty( $active_group_owners ) ? $items : array_filter(
		$items,
		function( $link ) use ( $group_settings_page_url ) {
			return $link->url !== $group_settings_page_url;
		}
	);
};

if ( defined( 'CFW_DEV_MODE' ) && CFW_DEV_MODE ) {
	add_filter( 'rcp_is_sandbox', '__return_true' );
}

function disable_resource_search_pagination( $query ) {
	if ( is_post_type_archive( 'resource' ) ) {
		$query->set( 'nopaging', true );
	}
}
add_action( 'pre_get_posts', 'disable_resource_search_pagination' );


function handle_checklist_question_ids_on_save( $value, $post_id, $field ) {
	return $value ? $value : bin2hex( random_bytes( 16 ) );
}
add_filter( 'acf/update_value/key=field_5e25d120c8f5e', 'handle_checklist_question_ids_on_save', 10, 3 );

function hide_interactive_checklist_question_id_fields() {
	echo '<style> .acf-field-5e25d120c8f5e { display: none; } </style>';
}
add_action( 'acf/input/admin_head', 'hide_interactive_checklist_question_id_fields' );


/**
 * Get the icon file name from set directory
 *
 * @author Jason Witt
 *
 * @param string $path Directory path to the icons.
 *
 * @return array
 */
function get_the_icons( $path ) {
	$dir    = trailingslashit( get_stylesheet_directory() ) . $path;
	$cdir   = scandir( $dir );
	$result = array();

	foreach ( $cdir as $key => $value ) {
		if ( ! in_array( $value, array( '.', '..', '.DS_Store' ), true ) ) {

			// If there are child directories, get the icons in there too.
			if ( is_dir( $dir . '/' . $value ) ) {
				foreach ( get_the_icons( $field, $path . '/' . $value ) as $file ) {
					$result[] = $file;
				}
			} else {
				$result[] = $value;
			}
		}
	}

	return $result;
}

/**
 * Dynamicaly populate Paragraph Icon select field
 * with the icons int assets/images/icons.
 *
 * @author Jason Witt
 *
 * @param array $field The field data.
 *
 * @return array
 */
function acf_load_paragrahp_icon_field_choices( $field ) {
	$field['choices'] = array();

	$path  = 'assets/images/icons';
	$files = get_the_icons( $path );

	foreach ( $files as $file ) {
		$name             = preg_replace( '/.[^.]*$/', '', $file );
		$choices[ $name ] = ucwords( str_replace( array( '-', '_' ), ' ', $name ) );
	}

	// loop through array and add to field 'choices'.
	if ( is_array( $choices ) ) {
		foreach ( $choices as $key => $value ) {
			$field['choices'][ $key ] = $value;
		}
	}

	return $field;
}

add_filter( 'acf/load_field/name=pic_icon', 'acf_load_paragrahp_icon_field_choices' );

add_filter( 'gform_confirmation_anchor', '__return_false' );


add_filter(
	'manage_page_posts_columns',
	function( $columns ) {
		return array_merge( $columns, array( 'template' => __( 'Template' ) ) );
	}
);

add_action(
	'manage_page_posts_custom_column',
	function( $column_key, $post_id ) {
		if ( $column_key == 'template' ) {
			$template = get_page_template_slug( $post_id );
			if ( ! empty( $template ) ) {
				echo $template;
			}
		}
	},
	10,
	2
);

/**
 * Add body class to share link pages
 *
 * @param array $classes The array of body classes.
 *
 * @return array
 */
function share_link_page_class( $classes ) {

	if ( fp_is_share_link() ) {
		$classes[] = 'share-link-page';
	}

	return $classes;
}
add_filter( 'body_class', 'share_link_page_class' );

/**
 * Filter Resource Type Facet
 */
function filter_resource_type_facet( $args ) {
	if ( $args['facet']['name'] !== 'resource_type' ) {
		return $args;
	}

	if ( ! empty( $args['values'] ) ) {
		foreach ( $args['values'] as $key => $value ) {
			$args['values'][ $key ]['facet_display_value'] = ucwords( str_replace( array( '-', '_' ), ' ', $value['facet_display_value'] ) );
		}
	}

	return $args;
}

add_filter( 'facetwp_facet_render_args', 'filter_resource_type_facet' );

/**
 * Edit flowchart SVG's
 */
function svg_upload( $upload ) {

	if ( ! fp_is_feature_active( 'svg_upload' ) ) {
		return $upload;
	}

	$file     = $upload['file'];
	$filetype = pathinfo( $file, PATHINFO_EXTENSION );

	if ( $filetype !== 'svg' ) {
		return $upload;
	}

	$contents = file_get_contents( $file );
	$contents = preg_replace( '/<!--(.|\n)*?-->/', '', $contents );

	$fonts = array(
		"'OpenSans'"             => 'opensans',
		"'OpenSans-Semibold'"    => 'opensans-semibold',
		"'MuseoSansRounded-100'" => 'museosansrounded-100',
		"'MuseoSansRounded-700'" => 'museosansrounded-700',
	);

	foreach ( $fonts as $key => $value ) {
		$contents = str_replace( $key, $value, $contents );
	}

	$contents = preg_replace( '/\>\s+\</m', '><', $contents );

	file_put_contents( $file, $contents );

	return $upload;
}

add_filter( 'wp_handle_upload', 'svg_upload', 99 );

function remove_pys() {
	$rcp_search = $_GET['rcpga-search'] ?? '';

	if ( ! empty( $rcp_search ) ) {
		wp_deregister_script( 'pys' );
	}
}

add_action( 'wp_print_scripts', 'remove_pys' );


/**
 * Upgrade/Downgrade membership prorate message
 */
function custon_membership_prorate_message( $message ) {
	return sprintf( '<p>%s</p>', __( 'If you upgrade your account, the new membership will be prorated up to %s for the first payment. Prorated prices are shown below.', 'rcp' ) );
}

add_filter( 'rcp_registration_prorate_message', 'custon_membership_prorate_message' );

/**
 * Redirect admins to wp-login.php for 2FA
 */
function kill_rcp_admin_login( $post ) {
	if ( is_email( $_POST['rcp_user_login'] ) && ! username_exists( $_POST['rcp_user_login'] ) ) {
		$user = get_user_by( 'email', $_POST['rcp_user_login'] );
	} else {
		$user = get_user_by( 'login', $_POST['rcp_user_login'] );
	}

	if ( empty( $user->roles ) ) {
		return;
	}

	foreach ( $user->roles as $role ) {
		if ( $role === 'administrator' ) {
			wp_safe_redirect( home_url( 'wp-login.php' ) );
			exit;
		}
	}
}

add_action( 'rcp_before_form_errors', 'kill_rcp_admin_login' );

/**
 * HelpScout Beacon
 */
function helpscout_beacon() {
	$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
	$slug         = isset( $current_page->post_name ) ? $current_page->post_name : '';
	$name         = isset( $current_page->name ) ? $current_page->name : '';

	if ( $slug === 'blog' ) {
		?>
<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});</script>
<script type="text/javascript">window.Beacon('init', '761b9639-9e31-4a3b-8fd1-12f7f486de17')</script>
		<?php
	}

	if ( $name === 'resource' ) {
		?>
<script type="text/javascript">!function(e,t,n){function a(){var e=t.getElementsByTagName("script")[0],n=t.createElement("script");n.type="text/javascript",n.async=!0,n.src="https://beacon-v2.helpscout.net",e.parentNode.insertBefore(n,e)}if(e.Beacon=n=function(t,n,a){e.Beacon.readyQueue.push({method:t,options:n,data:a})},n.readyQueue=[],"complete"===t.readyState)return a();e.attachEvent?e.attachEvent("onload",a):e.addEventListener("load",a,!1)}(window,document,window.Beacon||function(){});</script>
<script type="text/javascript">window.Beacon('init', '31ed736f-3e73-43cd-aaee-1155ff338d24')</script>
		<?php
	}
}

add_action( 'wp_footer', 'helpscout_beacon' );

function member_updagrade_discount( $button ) {

	if ( empty( $button ) ) {
		return $button;
	}
	$url = ! empty( $button['url'] ) && strpos( $button['url'], 'mailto' ) === false ? $button['url'] : '';

	if ( empty( $url ) ) {
		return $button;
	}

	$group_id = (int) fp_get_group_id( get_current_user_id() );

	if ( empty( $group_id ) ) {
		return $button;
	}

	$group_discount_code = ( new FP_Core\Group_Settings\Settings\GroupMembersDiscountCode() )->get( $group_id );

	if ( empty( $group_discount_code ) ) {
		return $button;
	}

	$button['url'] = add_query_arg( 'discount', $group_discount_code, $url );

	return $button;
}

add_filter( 'price_block_button', 'member_updagrade_discount' );
