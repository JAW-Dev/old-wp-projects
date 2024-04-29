<?php

// * Start the engine.
require_once get_template_directory() . '/lib/init.php';

// Shortcodes.
$shortcodes_includes = array(
	'popular-tabs.php',
	'testimonials-slider.php',
	'columns.php',
	'current-openings.php',
	'cta.php',
	'course-catalog.php',
	'quiz-articles.php',
	'subscriber-count.php',
	'oim-lb.php',
	'member-content.php',
	'member-new-webinars.php',
	'member-recent-blog-quiz.php',
	'member-announcements.php',
	'nerd-notes.php',
	'page-image-set.php',
	'subpages.php',
	'user-quiz-list.php',
	'user-quiz-page-button.php',
);

foreach ( $shortcodes_includes as $shortcodes_include ) {
	$file = '/inc/shortcodes/' . $shortcodes_include;
	require_once get_stylesheet_directory() . $file;
}

// Helper functions.
$helper_includes = array(
	'has-shortcode.php',
	'sidebar-has-widget.php',
	'author-block.php',
	'buttons.php',
	'icon-content-row.php',
	'testimonial-block.php',
	'accordion-row.php',
	'primary-sidebar.php',
	'pricing-table.php',
	'hero-section.php',
	'slide-arrow.php',
	'events.php',
	'link-image.php',
	'speakers.php',
	'speaking-topics.php',
	'speaking-pricing-table.php',
	'speaking-availability.php',
	'random.php',
	'videos-grid.php',
	'podcast-reviews-slider.php',
	'faq-section.php',
	'form-section.php',
	'podcast-sub-buttons.php',
	'tabbed-podcast-topics.php',
	'conferences-list.php',
	'team-member-block.php',
	'consultants.php',
	'in-page-nav-section.php',
	'kitces-events.php',
	'in-post-header-nav.php',
	'member-nav.php',
	'member-templates.php',
	'timezone.php',
);

foreach ( $helper_includes as $helper_include ) {
	$file = '/inc/helpers/' . $helper_include;
	require_once get_stylesheet_directory() . $file;
}

// Classes.
$classes_includes = array(
	'user-quiz/UserQuizData.php',
	'user-quiz/UserQuizTable.php',
	'user-quiz/UserQuizCertification.php',
	'UserAccess.php',
	'GFShortcodeFinder.php',
	'Taxonomies.php',
	'AdvancedSearch.php',
	'MemberRoleCookies.php',
	'class-kitces-helpers.php',
	'class-kitces-svg.php',
	'class-kitces-advisor-news.php',
	'SearchQuery.php',
	'GuidMetaTag.php',
);

foreach ( $classes_includes as $classes_include ) {
	$file = '/inc/classes/' . $classes_include;
	require_once get_stylesheet_directory() . $file;
}

// Partials
require_once get_stylesheet_directory() . '/inc/partials/search-template-functions.php';
require_once get_stylesheet_directory() . '/inc/partials/entry-header.php';

// Gutenberg Blocks
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/agenda.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/block-stuff.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/content.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/cta-two-column.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/cta-video-image.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/faq.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/icon-blurbs.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/modal.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/team.php';
require_once get_stylesheet_directory() . '/inc/gutenberg-blocks/testimonials.php';

// ACF Helpers.
if ( class_exists( 'acf' ) ) {
	include_once get_stylesheet_directory() . '/inc/acf-helpers/gravity-form-field/acf-gravity_forms.php';
}

// * Child theme (do not remove)
define( 'CHILD_THEME_NAME', 'Michael Kitces' );
define( 'CHILD_THEME_URL', 'http://zachswinehart.com/' );
define( 'CHILD_THEME_VERSION', filemtime( get_stylesheet_directory() . '/style.css' ) );

// * Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );

// * Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

// * Add support for custom background
add_theme_support( 'custom-background' );

// * Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

// * Remove the edit link
add_filter( 'genesis_edit_post_link', '__return_false' );

/*
 use sub menu for sticky nav */
// remove_action( 'genesis_after_header', 'genesis_do_subnav' );
// add_action( 'genesis_before_header', 'genesis_do_subnav' );

/* remove header widget area */
unregister_sidebar( 'header-right' );

// * Unregister secondary sidebar
unregister_sidebar( 'sidebar-alt' );

/*
 move footer widgets below socket */
// remove_action( 'genesis_before_footer', 'genesis_footer_widget_areas' );
// add_action( 'genesis_after_footer', 'genesis_footer_widget_areas' );

remove_action( 'admin_menu', 'genesis_add_inpost_layout_box' );

/*
 remove socket  */
// remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
// remove_action( 'genesis_footer', 'genesis_do_footer' );
// remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );

/*
========================================================================
Optional Functions to Include
========================================================================*/
// *  Add Podcast custom post type
// require_once('lib/functions/podcast-cpt.php');



/*
========================================================================
Michael Kitces functions from old theme
========================================================================*/
require 'lib/functions/ot_events.php';
require 'lib/functions/acf-things.php';
require 'lib/functions/posttypes.php';
require 'lib/functions/taxonomies.php';
require 'lib/functions/metaboxes.php';
require 'lib/functions/widgets.php';
require 'inc/layout/header.php';


/*
========================================================================
Genesis search form shortcode
========================================================================*/
function genesis_search_form_func( $atts ) {
	return get_search_form();
}
add_shortcode( 'genesis_search_form', 'genesis_search_form_func' );



/*
========================================================================
Enqueue JS/CSS
========================================================================*/
require_once get_stylesheet_directory() . '/inc/scripts.php';

/**
 * Latvia
 */
function add_latvia_script() {
	global $post;

	$user_id        = is_user_logged_in() ? get_current_user_id() : '';
	$user_data      = is_user_logged_in() ? get_userdata( $user_id ) : '';
	$user_email     = is_user_logged_in() ? $user_data->user_email : '';
	$user_name      = is_user_logged_in() ? $user_data->first_name . ' ' . $user_data->last_name : 'Anonymous';
	$member_page_id = '';
	$post_data      = ! empty( $post ) ? get_post( $post->post_parent ) : '';
	$parent_slug    = ! empty( $post ) ? $post_data->post_name : '';
	$loggedin       = is_user_logged_in() && $parent_slug === 'member' || is_page( 'member' ) || is_singular( 'post' );
	$not_loggedin   = ! is_user_logged_in() && is_singular( 'post' );

	if ( $loggedin || $not_loggedin ) {
		?>
<script>
	(function(apiKey){
		(function(p,e,n,d,o){
			var v,w,x,y,z;o=p[d]=p[d]||{};o._q=[];
			v=['initialize','identify','updateOptions','pageLoad'];for(w=0,x=v.length;w<x;++w)(function(m){
			o[m]=o[m]||function(){o._q[m===v[0]?'unshift':'push']([m].concat([].slice.call(arguments,0)));};})(v[w]);
			y=e.createElement(n);y.async=!0;y.src='https://cdn.pendo.io/agent/static/'+apiKey+'/pendo.js';
			z=e.getElementsByTagName(n)[0];z.parentNode.insertBefore(y,z);})(window,document,'script','pendo');
			pendo.initialize({
				visitor: {
					id: '<?php echo esc_html( $user_id ); ?>',
					email: '<?php echo esc_html( $user_email ); ?>',
					full_name: '<?php echo esc_html( $user_name ); ?>',
				},
				account: {}
			});
	})('6b711c80-79ff-4959-6ff3-db61c139a711');
</script>
		<?php
	}
}
add_action( 'wp_head', 'add_latvia_script' );

/*
========================================================================
 Add CE banner before content
========================================================================*/

add_action( 'genesis_entry_header', 'cgd_ce_boxes', 1 );
function cgd_ce_boxes() {
	$prefix             = '_kitces_';
	$show_banner        = get_post_meta( get_the_ID(), $prefix . 'show_ce_banner', true );
	$show_ethics_banner = get_post_meta( get_the_ID(), $prefix . 'show_ce_ethics_banner', true );
	$show_ce_old_banner = get_post_meta( get_the_ID(), $prefix . 'show_ce_old_banner', true );
	$banner_classes     = null;
	$logged_in          = is_user_logged_in();
	$quiz_page_id       = get_post_meta( get_the_ID(), 'ce_quiz_page', true );
	$has_quiz_access    = false;

	$quiz_url = null;
	if ( ! empty( $quiz_page_id ) && 'on' !== $show_ce_old_banner ) {
		$quiz_url        = get_permalink( $quiz_page_id );
		$has_quiz_access = $logged_in && kitces_member_can_access_post( $quiz_page_id, get_current_user_id() );
	}

	if ( empty( $quiz_url ) ) {
		$quiz_link = '/member/';
	} else {
		$quiz_link = $quiz_url;
	}

	if ( $show_ethics_banner === 'on' ) {
		$banner_classes = 'ethics-banner';
	}

	if ( $show_banner === 'on' ) {
		?>
		<!--googleoff: index-->
		<div class="ce-banner <?php echo $banner_classes; ?>" data-answers="<?php echo $quiz_page_id; ?>">
			<div class="ce-banner-content">
				<div class="first-side">
					<svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g stroke="#000" stroke-linejoin="round" fill="none" fill-rule="evenodd"><path d="M17 8.5l-7.5 7L7 13" stroke-linecap="round"/><path d="M23.5 12c0-.979-.705-1.79-1.634-1.962a2.002 2.002 0 00.759-2.438 1.999 1.999 0 00-2.262-1.189 1.998 1.998 0 00-.233-2.543 2 2 0 00-2.542-.233 1.996 1.996 0 00-3.627-1.502 1.996 1.996 0 00-3.926 0 1.996 1.996 0 00-3.627 1.502 2 2 0 00-2.542.234 1.998 1.998 0 00-.233 2.542 1.995 1.995 0 00-1.503 3.626 1.996 1.996 0 000 3.926 1.997 1.997 0 001.503 3.627 2 2 0 00.233 2.542 2 2 0 002.542.235 1.998 1.998 0 003.627 1.501A2 2 0 0012 23.5c.979 0 1.788-.703 1.961-1.632a1.999 1.999 0 002.438.757 1.998 1.998 0 001.189-2.26 1.997 1.997 0 002.775-2.775 2 2 0 002.262-1.188 2 2 0 00-.759-2.439A1.999 1.999 0 0023.5 12h0z"/></g></svg>
					<?php if ( $show_ce_old_banner === 'on' || ! $logged_in ) : ?>
						<?php if ( $show_ethics_banner === 'on' ) : ?>
							<h3>Earn CFP Ethics CE for reading articles like this</h3>
						<?php else : ?>
							<h3>Want CE Credit for reading articles like this?</h3>
						<?php endif; ?>
					<?php else : ?>
						<?php if ( ! empty( $quiz_url ) ) : ?>
							<?php if ( $show_ethics_banner === 'on' ) : ?>
								<h3>Earn CFP Ethics CE For Reading This Article</h3>
							<?php else : ?>
								<h3>Want CE Credit for reading this article?</h3>
							<?php endif; ?>
						<?php else : ?>
							<?php if ( $show_ethics_banner === 'on' ) : ?>
								<h3>CFP Ethics CE For This Article Will Be Available Next Month!</h3>
							<?php else : ?>
								<h3>CE For This Article Will Be Available Next Month!</h3>
							<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</div>
				<?php if ( $has_quiz_access ) : ?>
					<?php if ( ! empty( $quiz_url ) ) : ?>
						<a class="button button-small" href="<?php echo esc_url( $quiz_link ); ?>">Take Quiz Now</a>
					<?php endif; ?>
				<?php else : ?>
					<a class="button button-small" href="/get-cfp-ce-credit/">Learn More!</a>
				<?php endif; ?>

			</div>
		</div>
		<!--googleon: index -->
		<?php
	}
}


/*
========================================================================
 Rebuild entry footer markup
 https://sridharkatakam.com/adding-missing-entry-footer-markup-genesis/
========================================================================*/
// * Remove the built-in Entry Footer markup
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_open', 5 );
remove_action( 'genesis_entry_footer', 'genesis_entry_footer_markup_close', 15 );

add_action( 'genesis_entry_footer', 'sk_entry_footer_markup_open', 5 );
/**
 * Echo the opening structural markup for the entry footer.
 *
 * Context: Everywhere except on static Pages.
 *
 * @author Sridhar Katakam
 * @link   http://sridharkatakam.com/
 */
function sk_entry_footer_markup_open() {
	if ( 'page' === get_post_type() ) {
		return;
	}

	printf( '<footer %s>', genesis_attr( 'entry-footer' ) );
}

add_action( 'genesis_entry_footer', 'sk_entry_footer_markup_close', 15 );
/**
 * Echo the closing structural markup for the entry footer.
 *
 * Context: Everywhere except on static Pages.
 */
function sk_entry_footer_markup_close() {
	if ( 'page' === get_post_type() ) {
		return;
	}

	echo '</footer>';
}

// edit the entry footer
add_filter( 'genesis_post_meta', 'sp_post_meta_filter' );
function sp_post_meta_filter( $post_meta ) {
	if ( is_single() ) {
		// include cpt taxonomies: categories/tags
		$post_meta = do_shortcode( '[post_categories sep="" before="" after=""] [post_terms taxonomy="news-category" before="" sep="" after=""] [post_terms taxonomy="video-category" before="" sep="" after=""]' );
	}

}


// edit the way the post info displays
add_filter( 'genesis_post_info', 'sp_post_info_filter' );
function sp_post_info_filter( $post_info ) {
	// $post_info = '[post_date format="M j, Y" after=" "] By: [post_author_posts_link]';
	// $post_info = '[post_date format="Y-m-d\TH:i:sP" after=" "]';
	$post_info = '[post_date format="F j, Y h:i a" after=" "] [post_comments zero="0 Comments" one="1 Comment" more="% Comments" before="" sep="" after="" ] [post_categories sep="" before="CATEGORY: " after=""]';
	return $post_info;
}


/*
========================================================================
Archive Navigation
========================================================================*/
// * Customize the next page link
add_filter( 'genesis_next_link_text', 'z_next_page_link' );
function z_next_page_link( $text ) {
	return 'Next &#x000BB;';
}

// * Customize the previous page link
add_filter( 'genesis_prev_link_text', 'z_previous_page_link' );
function z_previous_page_link( $text ) {
	return '&#x000AB; Previous';
}

// Move Primary Nav to Genesis Header Right
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_header_right', 'genesis_do_nav' );
add_action( 'genesis_header', 'objectiv_do_mobile_nav_trigger' );

function objectiv_do_mobile_nav_trigger() {
	echo "<button class='mobile-nav-trigger-wrap nav-trigger'>";
	echo "<svg class='mobile-nav-open' width='30' height='22' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M28.714 8.8H1.286C.103 8.8 0 9.783 0 11s.103 2.2 1.286 2.2h27.428C29.897 13.2 30 12.217 30 11s-.103-2.2-1.286-2.2zm0 8.8H1.286C.103 17.6 0 18.583 0 19.8S.103 22 1.286 22h27.428C29.897 22 30 21.017 30 19.8s-.103-2.2-1.286-2.2zM1.286 4.4h27.428C29.897 4.4 30 3.417 30 2.2S29.897 0 28.714 0H1.286C.103 0 0 .983 0 2.2s.103 2.2 1.286 2.2z' fill='#fff'/></svg>";
	echo "<svg class='mobile-nav-close' width='26' height='26' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M13 11.162L4.515 2.677a1 1 0 0 0-1.414 0l-.424.424a1 1 0 0 0 0 1.414L11.162 13l-8.485 8.485a1 1 0 0 0 0 1.414l.424.424a1 1 0 0 0 1.414 0L13 14.838l8.485 8.485a1 1 0 0 0 1.414 0l.424-.424a1 1 0 0 0 0-1.414L14.838 13l8.485-8.485a1 1 0 0 0 0-1.414l-.424-.424a1 1 0 0 0-1.414 0L13 11.162z' fill='#fff'/></svg>";
	echo '</button>';
}

// Adds Menus
add_theme_support(
	'genesis-menus',
	array(
		'primary'             => 'Primary Navigation Menu',
		'nev_menu'            => 'NEV Navigation Menu',
		'blog_menu'           => 'Blog Navigation Menu',
		'mobile_menu'         => 'Mobile Menu',
		'member_sidebar_prem' => 'Member Sidebar - Premier',
		'member_sidebar_bas'  => 'Member Sidebar - Basic',
		'member_sidebar_stud' => 'Member Sidebar - Student',
		'member_sidebar_read' => 'Member Sidebar - Reader',
	)
);

// Add the Mobile Menu After the Header
add_action( 'genesis_after_header', 'objectiv_do_mobile_nav_navigation', 1 );
function objectiv_do_mobile_nav_navigation() {
	$member_login        = mk_acf_get_field( 'members_login_link', 'options' );
	$member_home         = mk_acf_get_field( 'members_home_link', 'options' );
	$member_home_readers = mk_acf_get_field( 'members_home_readers', 'options' );
	$facebook            = mk_acf_get_field( 'mobile_social_facebook', 'options' );
	$twitter             = mk_acf_get_field( 'mobile_social_twitter', 'options' );
	$linkedin            = mk_acf_get_field( 'mobile_social_linkedin', 'options' );
	$youtube             = mk_acf_get_field( 'mobile_social_youtube', 'options' );
	$logged_in           = is_user_logged_in();

	if ( kitces_is_valid_reader_member() && ! empty( $member_home_readers ) ) {
		$member_home = $member_home_readers;
	}

	$advanced_search = new AdvancedSearch();

	?>
	<div class='mobile-nav-wrap'>
		<div class="mobile-top-buttons">
			<?php if ( ! $logged_in && ! empty( $member_login ) && is_array( $member_login ) ) : ?>
				<li class="green-button menu-item"><a target="<?php echo $member_login['target']; ?>" href="<?php echo $member_login['url']; ?>"><?php echo $member_login['title']; ?></a></li>
			<?php endif; ?>
			<?php if ( $logged_in && ! empty( $member_home ) && is_array( $member_home ) ) : ?>
				<li class="green-button menu-item"><a target="<?php echo $member_home['target']; ?>" href="<?php echo $member_home['url']; ?>"><?php echo $member_home['title']; ?></a></li>
			<?php endif; ?>
			<li class="blue-button search mobile-search-toggle open-search menu-item"><a href="#">Search</a></li>
			<li class="blue-button mobile-search-toggle close-search menu-item"><a href="#">Close Search</a></li>
		</div>
		<?php
		$advanced_search->mobile_form();
		objectiv_do_mobile_menu();
		?>
		<?php if ( ! empty( $facebook ) || ! empty( $twitter ) || ! empty( $linkedin ) || ! empty( $youtube ) ) : ?>
			<div class="mobile-connect-wrap">
				<div class="mc-title">Connect With Me</div>
				<?php if ( ! empty( $facebook ) && is_array( $facebook ) ) : ?>
					<a href="<?php echo $facebook['url']; ?>" target="_blank" title="Connect on Facebook">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#56A3D9" d="M0 0h48v48H0z"/><path d="M24 8C15.163 8 8 15.163 8 24s7.163 16 16 16 16-7.163 16-16S32.837 8 24 8zm3.79 11.057h-2.405c-.285 0-.602.375-.602.873v1.737h3.009l-.455 2.476h-2.554v7.435h-2.838v-7.435H19.37v-2.476h2.575V20.21c0-2.09 1.45-3.788 3.44-3.788h2.405v2.635z" fill="#fff"/></svg>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $twitter ) && is_array( $twitter ) ) : ?>
					<a href="<?php echo $twitter['url']; ?>" target="_blank" title="Connect on Twitter">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#56A3D9" d="M0 0h48v48H0z"/><path d="M24 8C15.163 8 8 15.163 8 24s7.163 16 16 16 16-7.163 16-16S32.837 8 24 8zm6.508 13.107c.007.136.009.273.009.406 0 4.167-3.169 8.969-8.965 8.969-1.78 0-3.437-.52-4.83-1.417.245.03.496.042.751.042a6.312 6.312 0 0 0 3.914-1.349 3.158 3.158 0 0 1-2.944-2.186 3.169 3.169 0 0 0 1.422-.055 3.154 3.154 0 0 1-2.528-3.09v-.039a3.16 3.16 0 0 0 1.428.395 3.15 3.15 0 0 1-1.402-2.625c0-.576.155-1.12.427-1.585a8.96 8.96 0 0 0 6.495 3.295 3.152 3.152 0 0 1 5.37-2.875 6.328 6.328 0 0 0 2-.765 3.166 3.166 0 0 1-1.385 1.745 6.329 6.329 0 0 0 1.81-.498 6.39 6.39 0 0 1-1.572 1.632z" fill="#fff"/></svg>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $linkedin ) && is_array( $linkedin ) ) : ?>
					<a href="<?php echo $linkedin['url']; ?>" target="_blank" title="Connect on LinkedIn">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#56A3D9" d="M0 0h48v48H0z"/><path d="M24 8C15.163 8 8 15.163 8 24s7.163 16 16 16 16-7.163 16-16S32.837 8 24 8zm-3.917 22.632h-3.24V20.205h3.24v10.427zm-1.64-11.707c-1.023 0-1.685-.725-1.685-1.622 0-.915.682-1.618 1.727-1.618s1.685.703 1.705 1.618c0 .897-.66 1.622-1.747 1.622zm13.474 11.707h-3.24v-5.779c0-1.345-.47-2.258-1.642-2.258-.895 0-1.427.618-1.662 1.213-.086.212-.108.512-.108.81v6.012h-3.242v-7.1c0-1.302-.041-2.39-.085-3.327h2.815l.149 1.449h.065c.426-.68 1.471-1.684 3.22-1.684 2.131 0 3.73 1.429 3.73 4.499v6.165z" fill="#fff"/></svg>
					</a>
				<?php endif; ?>

				<?php if ( ! empty( $youtube ) && is_array( $youtube ) ) : ?>
					<a href="<?php echo $youtube['url']; ?>" target="_blank" title="Connect on YouTube">
						<svg width="48" height="48" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill="#56A3D9" d="M0 0h48v48H0z"/><path d="M26.672 23.722l-3.744-1.747c-.326-.152-.595.018-.595.38v3.29c0 .362.269.532.595.38l3.742-1.747c.328-.153.328-.403.002-.556zM24 8C15.163 8 8 15.163 8 24s7.163 16 16 16 16-7.163 16-16S32.837 8 24 8zm0 22.5c-8.19 0-8.333-.738-8.333-6.5 0-5.762.143-6.5 8.333-6.5s8.333.738 8.333 6.5c0 5.762-.143 6.5-8.333 6.5z" fill="#fff"/></svg>
					</a>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

// Output the actual Mobile Menu
function objectiv_do_mobile_menu() {
	if ( has_nav_menu( 'mobile_menu' ) ) {
		echo "<div class='mobile-menu-wrap'>";
		wp_nav_menu( array( 'theme_location' => 'mobile_menu' ) );
		echo '</div>';
	}
}

// Add the Nerds Eye View Menu After the Header
add_action( 'genesis_after_header', 'objectiv_do_nev_navigation', 1 );
function objectiv_do_nev_navigation() {
	$advanced_search = new AdvancedSearch();

	?>
	<div class='after-header-nev'>
		<div class='wrap'>
			<div class='after-header-nev-inner'>
				<div class='nev-left'>
					<a href="/">
						<img src="<?php echo get_stylesheet_directory_uri() . '/lib/images/kitces-nerds-eye-view.png'; ?>" alt="Nerds Eye View">
					</a>
				</div>
				<div class='nev-right'>
					<?php
					objectiv_do_nev_menu();
					$advanced_search->form();
					?>
				</div>
			</div>
		</div>
	</div>
	<?php
}

// Output the actual NEV Menu
function objectiv_do_nev_menu() {
	if ( has_nav_menu( 'nev_menu' ) ) {
		echo "<div class='nev-menu-wrap'>";
		wp_nav_menu( array( 'theme_location' => 'nev_menu' ) );
		echo '</div>';
	}
}

/*
========================================================================
Footer text display
========================================================================*/

remove_action( 'genesis_footer', 'genesis_do_footer' );
add_action( 'genesis_footer', 'cgd_custom_footer' );
function cgd_custom_footer() {
	?>
	<p>&copy; <?php echo date( 'Y' ); ?> Kitces.com. All Rights Reserved</p>
	<?php
}




/*
======================================================
Guest Image & Podcast Player
======================================================*/
// add_action( 'genesis_entry_header', 'add_guest_image', 1 ); /* move this inside the article element */
function add_guest_image() {
	$img = types_render_field(
		'guest-image',
		array(
			'width'        => '80',
			'height'       => '80',
			'resize'       => 'crop',
			'proportional' => 'false',
		)
	);

	$player = types_render_field(
		'top-of-post-podcast-player-shortcode',
		array(
			'output' => 'raw',
		)
	);

	if ( $player ) {
		echo '<div class="top-player-bar">';
		if ( $img ) {
			echo '<div class="guest-image">' . $img . '</div>';
		} else {
			echo '<div class="guest-image"><img src="' . get_stylesheet_directory_uri() . '/lib/images/podcast-default-img@2x.png" /></div>';
		}

			echo do_shortcode( $player );
		echo '</div>';
	}

}




/*
======================================================
Hide page titles
======================================================*/
add_action( 'get_header', 'z_remove_page_titles' );
function z_remove_page_titles() {
	if ( ! is_single() && ! is_archive() && ! is_home() && ! is_page_template( 'home.php' ) && ! is_search() && ! is_404() && ! is_page_template( 'front-page.php' ) && ! is_front_page() ) {
		// if( !is_single() && !is_front_page() && !is_archive() && !is_home() && !is_page_template( 'home.php' ) && !is_search() && !is_404() ) {
		remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
	}
}




/*
========================================================================
Large image size
========================================================================*/
// Add the new image size to WP
add_image_size( 'Large Header Image', 1920, 250, true );
add_image_size( 'mk-xl', 2000, 2000, false );

/*
========================================================================
Large square image size
========================================================================*/
// Add the new image size to WP
add_image_size( 'small-square', 400, 400, true );
add_image_size( 'large-square', 800, 800, true );
add_image_size( 'podcast-wide', 1150, 476, true );
add_image_size( 'author-photo', 200, 200, true );
add_image_size( 'large-rectangular', 800, 600, true );




/*
======================================================
Heading images for podcasts
======================================================*/
// add_action( 'genesis_after_header','add_heading_image' );
function add_heading_image() {

	/*
	$title_prefix_raw = types_render_field( "title-prefix", array(
		"output" => "raw"
	) );
	$title_prefix = '<span>' . $title_prefix_raw . '</span>';

	$title_base_raw = types_render_field( "title-base", array(
		"output" => "raw"
	) );*/

	if ( is_post_type_archive( 'post' ) || is_singular( 'post' ) || is_page_template( 'page_blog.php' ) || is_home() || is_category( 'blog' ) ) {
		// if ( is_page_template('page_blog.php') || is_tag() ){
		if ( in_category( 'podcast' ) && is_singular( 'post' ) ) {
			echo '<div class="entry-header-wrapper podcasts custom-header-img"><div class="gradient-overlay"><div class="wrap">';
			echo '<h1 class="entry-title">Podcast</h1>';
			echo '</div></div></div>';
		} else {
			echo '<div class="entry-header-wrapper blog custom-header-img"><div class="gradient-overlay"><div class="wrap">';
			echo '<h1 class="entry-title">Blog</h1>';
			echo '</div></div></div>';
		}
	} //special header for podcast pages
	elseif ( is_singular( 'podcast' ) || is_post_type_archive( 'podcast' ) || is_tax( 'podcast_tag' ) || is_category( 'podcast' ) ) {

		echo '<div class="entry-header-wrapper podcasts custom-header-img"><div class="gradient-overlay"><div class="wrap">';
		echo '<h1 class="entry-title">Podcast</h1>';
		echo '</div></div></div>';

	} //special header for video cpt pages
	elseif ( is_singular( 'video' ) || is_post_type_archive( 'video' ) ) {

		echo '<div class="entry-header-wrapper videos custom-header-img"><div class="gradient-overlay"><div class="wrap">';
		echo '<h1 class="entry-title">Videos</h1>';
		echo '</div></div></div>';

	} //special header for news cpt pages
	elseif ( is_singular( 'news' ) || is_post_type_archive( 'news' ) ) {

		echo '<div class="entry-header-wrapper news custom-header-img"><div class="gradient-overlay"><div class="wrap">';
		echo '<h1 class="entry-title">News</h1>';
		echo '</div></div></div>';

	} /*
	elseif( is_page() && !is_front_page() ) {
		if ( !empty($title_prefix_raw) && !empty($title_base_raw) ) {
			echo '<div class="entry-header-wrapper podcasts"><div class="gradient-overlay"><div class="wrap">';
			echo '<h1 class="entry-title">' . $title_prefix . $title_base_raw . '</h1>';
			echo '</div></div></div>';
		} else {
			echo '<div class="entry-header-wrapper podcasts"><div class="gradient-overlay"><div class="wrap">';
			echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
			echo '</div></div></div>';
		}
	}*/

	/*
	elseif( is_page() && !is_front_page() ) {
		echo '<div class="entry-header-wrapper podcasts"><div class="gradient-overlay"><div class="wrap">';
		echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
		echo '</div></div></div>';
	}*/

	// special header for pages
	elseif ( is_singular() && ! is_front_page() ) {
		$img        = genesis_get_image(
			array(
				'format' => 'url',
				'size'   => 'Large Header Image',
			)
		);
		$extraclass = '';
		$imgoutput  = '';
		if ( has_post_thumbnail() ) {
			$imgoutput  = ' style="background-image:url(' . $img . ');"';
			$extraclass = 'custom-header-img';
		}
		echo '<div class="entry-header-wrapper page ' . $extraclass . '" ' . $imgoutput . '><div class="gradient-overlay"><div class="wrap">';
		echo '<h1 class="entry-title">' . get_the_title() . '</h1>';
		echo '</div></div></div>';

	} elseif ( is_404() ) {
		echo '<div class="entry-header-wrapper podcasts"><div class="gradient-overlay"><div class="wrap">';
		echo '<h1 class="entry-title">Page Not Found</h1>';
		echo '</div></div></div>';
	}

}





/*
======================================================
Read more link
======================================================*/

/* Setting: display post content -> read more line in body */
add_filter( 'get_the_content_more_link', 'sp_read_more_link' );
function sp_read_more_link() {
	return '... <a class="more-link" href="' . get_permalink() . '">Read More...</a>';
}

/* Setting: display post excerpt */
add_filter( 'excerpt_more', 'sp_cgd_read_more_link' );
add_filter( 'the_content_more_link', 'sp_cgd_read_more_link' );
function sp_cgd_read_more_link() {
	return '<a class="more-link" href="' . get_permalink() . '">Read More...</a>';
}

/*
======================================================
Add menu classes support for custom post types (.current_page_parent)
======================================================*/
add_filter( 'nav_menu_css_class', 'current_type_nav_class', 10, 2 );
function current_type_nav_class( $classes, $item ) {
	// Get post_type for this post
	$post_type = get_query_var( 'post_type' );

	// Removes current_page_parent class from blog menu item
	if ( get_post_type() == $post_type ) {
		$classes = array_filter( $classes, 'get_current_value' );
	}

	// Go to Menus and add a menu class named: {custom-post-type}-menu-item
	// This adds a current_page_parent class to the parent menu item
	if ( in_array( $post_type . '-menu-item', $classes ) ) {
		array_push( $classes, 'current_page_parent' );
	}

	return $classes;
}
function get_current_value( $element ) {
	return ( $element != 'current_page_parent' );
}


/*
======================================================
Widget Area Below Header & Nav
======================================================*/
genesis_register_sidebar(
	array(
		'id'          => 'below-header-widget',
		'name'        => __( 'Header Optin Widget' ),
		'description' => __( 'This is the widget section below the header & main navigation.' ),
	)
);

add_action( 'genesis_after_header', 'below_header_widget', 10 );
function below_header_widget() {
	if ( is_single() || get_post_meta( get_the_ID(), 'show_optin_widget__enable_optin_widget', true ) ) {
		genesis_widget_area(
			'below-header-widget',
			array(
				'before' => '<div class="below-header-widget widget-area">',
				'after'  => '</div>',
			)
		);
	}
}



/*
======================================================
Meta box for Optin Widget
======================================================*/

function show_optin_widget__get_meta( $value ) {
	global $post;

	$field = get_post_meta( $post->ID, $value, true );
	if ( ! empty( $field ) ) {
		return is_array( $field ) ? stripslashes_deep( $field ) : stripslashes( wp_kses_decode_entities( $field ) );
	} else {
		return false;
	}
}

// create meta box
function show_optin_widget__add_meta_box() {
	add_meta_box(
		'show_optin_widget_-show-optin-widget',
		__( 'Show Optin Widget?', 'show_optin_widget_' ),
		'show_optin_widget__html',
		'post',
		'normal',
		'low'
	);
	add_meta_box(
		'show_optin_widget_-show-optin-widget',
		__( 'Show Optin Widget?', 'show_optin_widget_' ),
		'show_optin_widget__html',
		'page',
		'normal',
		'low'
	);
}
add_action( 'add_meta_boxes', 'show_optin_widget__add_meta_box' );

// meta box display
function show_optin_widget__html( $post ) {
	wp_nonce_field( '_show_optin_widget__nonce', 'show_optin_widget__nonce' );
	?>

	<p>

		<input type="checkbox" name="show_optin_widget__enable_optin_widget" id="show_optin_widget__enable_optin_widget" value="enable-optin-widget" <?php echo ( show_optin_widget__get_meta( 'show_optin_widget__enable_optin_widget' ) === 'enable-optin-widget' ) ? 'checked' : ''; ?>>
		<label for="show_optin_widget__enable_optin_widget"><?php _e( 'Enable Optin Widget', 'show_optin_widget_' ); ?></label>	</p>
																		<?php
}

// save meta box
function show_optin_widget__save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! isset( $_POST['show_optin_widget__nonce'] ) || ! wp_verify_nonce( $_POST['show_optin_widget__nonce'], '_show_optin_widget__nonce' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( isset( $_POST['show_optin_widget__enable_optin_widget'] ) ) {
		update_post_meta( $post_id, 'show_optin_widget__enable_optin_widget', esc_attr( $_POST['show_optin_widget__enable_optin_widget'] ) );
	} else {
		update_post_meta( $post_id, 'show_optin_widget__enable_optin_widget', null );
	}
}
add_action( 'save_post', 'show_optin_widget__save' );

/*
	Usage: show_optin_widget__get_meta( 'show_optin_widget__enable_optin_widget' )
*/


/*
======================================================
Create shortcode for Events
======================================================*/
add_shortcode( 'display_events', 'display_events' );
// [display_events]

function display_events() {
	// if( $flexible_posts->have_posts() ):

	$output = '<ul class="dpe-flexible-posts listed_posts events">';

	$args = array(
		'post_type'      => 'event',
		'posts_per_page' => 4,
	);
	$loop = new WP_Query( $args );

	while ( $loop->have_posts() ) :
		$loop->the_post();

		$event_timestamp = date( get_post_custom_values( 'ot_e_date' )[0] );
		$location        = get_post_custom_values( 'ot_e_location' )[0];
		$label           = get_post_custom_values( 'ot_e_label' )[0];

		$output         .= '<li id="post-' . get_the_ID() . '">';
			$output     .= '<div class="entry-meta">';
				$output .= '<span class="entry-date">' . gmdate( 'M\<\b\r\/\>d', $event_timestamp ) . '</span>';
			$output     .= '</div>';

			$output             .= '<div class="event-content">';
				$output         .= '<div class="">';
					$output     .= '<a style="text-decoration:none;" href="' . get_the_permalink() . '">';
						$output .= '<h3 class="title">' . get_the_title() . '</h3>';
					$output     .= '</a>';
				$output         .= '</div>';

		if ( ! empty( $location ) ) {
			$output     .= '<div class="event-location">';
				$output .= '<i class="fas fa-map-marker"></i> ' . $location;
			$output     .= '</div>';
		}

				/*
				if (!empty($label) ) {
					$output .= '<div class="event-label">';
						$output .= '<i class="fas fa-file-text-o"></i> ' . $label;
					$output .= '</div>';
				}  */

				$output .= '<div class="clear"></div>';
				$output .= '<div><p>' . get_the_content() . '</p></div>';
			$output     .= '</div>';
		$output         .= '</li> ';
		wp_reset_postdata();
	endwhile;

	$output .= '</ul>';

	// endif; // End have_posts()

	return $output;
}

function member_nav_bar() {
	global $post;
	$post_id        = ! empty( $post->ID ) ? $post->ID : '';
	$parent_id      = ! empty( $post->post_parent ) ? $post->post_parent : '';
	$parent_slug    = $parent_id ? get_post_field( 'post_name', $parent_id ) : '';
	$member_slug    = 'member';
	$is_member_page = is_page( $member_slug ) || $parent_slug === $member_slug;
	$template_file  = get_post_meta( $post_id, '_wp_page_template', true );

	$display_member_nav = $post_id && $is_member_page && is_memberium_protected( $post_id ) && $template_file !== 'template-member-page.php';

	if ( $display_member_nav ) {
		get_template_part( 'member', 'nav' );
	}
}

add_action( 'genesis_after_header', 'member_nav_bar' );


function is_memberium_protected( $post_id ) {
	$post_id         = (int) $post_id;
	$post_metas      = get_post_meta( $post_id );
	$protection_keys = array(
		'_is4wp_access_tags',
		'_is4wp_anonymous_only',
		'_is4wp_any_loggedin_user',
		'_is4wp_any_membership',
		'_is4wp_contact_ids',
		'_is4wp_membership_levels',
		'_memberium_any_membership',
		'_memberium_membership_levels',
		'_memberium_access_tags',
		'_memberium_access_tags2',
	);

	if ( ! is_array( $post_metas ) ) {
		return false; // no post meta on post
	}

	foreach ( $post_metas as $key => $value ) {
		if ( in_array( $key, $protection_keys ) ) {
			$value = implode( '', $value );
			if ( ! empty( $value ) ) {
				return true;
			}
		}
	}
	return false;
}

/**
 * Register speaking sidebar
 */
function register_announcements_sidebar() {

	register_sidebar(
		array(
			'name'          => 'Blog Pages Announcements',
			'id'            => 'announcements_sidebar',
			'before_widget' => '<div id="%1$s" class="announcement">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'register_announcements_sidebar' );

add_action( 'genesis_before_loop', 'cgd_announcements' );
function cgd_announcements() {
	if ( is_active_sidebar( 'announcements_sidebar' ) && ! is_front_page() && is_singular( 'post' ) ) {
		echo "<div class='announcements-sidebar-wrap'>";
		dynamic_sidebar( 'announcements_sidebar' );
		echo '</div>';
	}
}

/**
 * Register Members Area Announcements Sidebar
 */
function register_member_area_announcements_sidebar() {

	register_sidebar(
		array(
			'name'          => 'Member Area Announcements',
			'id'            => 'member_announcements_sidebar',
			'before_widget' => '<div id="%1$s" class="announcement">',
			'after_widget'  => '</div>',
			'before_title'  => '<h2>',
			'after_title'   => '</h2>',
		)
	);

}
add_action( 'widgets_init', 'register_member_area_announcements_sidebar' );

// * Enqueue Dashicons
add_action( 'wp_enqueue_scripts', 'enqueue_dashicons' );
function enqueue_dashicons() {

	wp_enqueue_style( 'dashicons' );

}

// * Customize search form input button text
add_filter( 'genesis_search_button_text', 'sk_search_button_text' );
function sk_search_button_text( $text ) {

	return esc_attr( '&#xf002;' );

}

// Remove default Jetpack share location
function jptweak_remove_share() {
	remove_filter( 'the_content', 'sharing_display', 19 );
	remove_filter( 'the_excerpt', 'sharing_display', 19 );

	if ( class_exists( 'Jetpack_Likes' ) ) {
		remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30 );
	}
}

add_action( 'loop_start', 'jptweak_remove_share' );

add_action( 'genesis_entry_content', 'cgd_jetpack_share', 1 );
function cgd_jetpack_share() {
	if ( is_singular( 'post' ) ) {
		echo '<div class="kitces-sharing is-inline">';

		echo '<div class="social-links-wrapper">';

		echo do_shortcode( '[shared_counts]' );

		echo '</div>';

		if ( function_exists( 'mk_favorite_posts' ) ) {
			mk_favorite_posts();
		}

		if ( function_exists( 'mk_get_star_ratings_output' ) ) {
			$star_ratings_output = mk_get_star_ratings_output();
			if ( ! empty( $star_ratings_output ) ) {
				echo $star_ratings_output;
			}
		}

		if ( function_exists( 'fontResizer_place' ) ) {
			?>
			<div class="desktop-resizer"><?php fontResizer_place(); ?></div>
			<?php
		}
		echo '</div>';
	}

	if ( function_exists( 'mk_favorite_posts_mobile' ) && is_singular( 'post' ) ) {
		mk_favorite_posts_mobile();
	}
}

add_filter(
	'shared_counts_display_output',
	function( $content, $location ) {
		$onclick = "window.print();if(typeof(_gaq) != 'undefined') { _gaq.push(['_trackEvent','PRINTFRIENDLY', 'print', 'NULL']); }else if(typeof(ga) != 'undefined') {  ga('send', 'event','PRINTFRIENDLY', 'print', 'NULL'); } return false;";
		$extra   = '<a
				href="#"
				rel="nofollow"
				class="shared-counts-button print nopdf"
				onclick="' . $onclick . '"
				title="Printer Friendly, PDF &amp; Email">
				<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="29.71875" height="32" viewBox="0 0 951 1024"><path d="M219.429 877.714h512v-146.286h-512v146.286zM219.429 512h512v-219.429h-91.429q-22.857 0-38.857-16t-16-38.857v-91.429h-365.714v365.714zM877.714 548.571q0-14.857-10.857-25.714t-25.714-10.857-25.714 10.857-10.857 25.714 10.857 25.714 25.714 10.857 25.714-10.857 10.857-25.714zM950.857 548.571v237.714q0 7.429-5.429 12.857t-12.857 5.429h-128v91.429q0 22.857-16 38.857t-38.857 16h-548.571q-22.857 0-38.857-16t-16-38.857v-91.429h-128q-7.429 0-12.857-5.429t-5.429-12.857v-237.714q0-45.143 32.286-77.429t77.429-32.286h36.571v-310.857q0-22.857 16-38.857t38.857-16h384q22.857 0 50.286 11.429t43.429 27.429l86.857 86.857q16 16 27.429 43.429t11.429 50.286v146.286h36.571q45.143 0 77.429 32.286t32.286 77.429z"></path></svg>
			</a>';

		$pos     = strpos( $content, '</div>' );
		$content = substr( $content, 0, $pos ) . $extra . substr( $content, $pos );

		return $content;
	},
	10,
	2
);

add_filter(
	'shared_counts_display_output',
	function( $content, $location ) {
		$onclick = "window.print();if(typeof(_gaq) != 'undefined') { _gaq.push(['_trackEvent','PRINTFRIENDLY', 'print', 'NULL']); }else if(typeof(ga) != 'undefined') {  ga('send', 'event','PRINTFRIENDLY', 'print', 'NULL'); } return false;";
		$extra   = '<a
				href="#"
				rel="nofollow"
				class="shared-counts-button print pdf"
				onclick="' . $onclick . '"
				title="Printer Friendly, PDF &amp; Email">
				<svg xmlns="http://www.w3.org/2000/svg" height="32" width="29.71875" viewBox="0 0 16 16"><path d="M4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H4zm0 1h8a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/><path d="M4.603 12.087a.81.81 0 0 1-.438-.42c-.195-.388-.13-.776.08-1.102.198-.307.526-.568.897-.787a7.68 7.68 0 0 1 1.482-.645 19.701 19.701 0 0 0 1.062-2.227 7.269 7.269 0 0 1-.43-1.295c-.086-.4-.119-.796-.046-1.136.075-.354.274-.672.65-.823.192-.077.4-.12.602-.077a.7.7 0 0 1 .477.365c.088.164.12.356.127.538.007.187-.012.395-.047.614-.084.51-.27 1.134-.52 1.794a10.954 10.954 0 0 0 .98 1.686 5.753 5.753 0 0 1 1.334.05c.364.065.734.195.96.465.12.144.193.32.2.518.007.192-.047.382-.138.563a1.04 1.04 0 0 1-.354.416.856.856 0 0 1-.51.138c-.331-.014-.654-.196-.933-.417a5.716 5.716 0 0 1-.911-.95 11.642 11.642 0 0 0-1.997.406 11.311 11.311 0 0 1-1.021 1.51c-.29.35-.608.655-.926.787a.793.793 0 0 1-.58.029zm1.379-1.901c-.166.076-.32.156-.459.238-.328.194-.541.383-.647.547-.094.145-.096.25-.04.361.01.022.02.036.026.044a.27.27 0 0 0 .035-.012c.137-.056.355-.235.635-.572a8.18 8.18 0 0 0 .45-.606zm1.64-1.33a12.647 12.647 0 0 1 1.01-.193 11.666 11.666 0 0 1-.51-.858 20.741 20.741 0 0 1-.5 1.05zm2.446.45c.15.162.296.3.435.41.24.19.407.253.498.256a.107.107 0 0 0 .07-.015.307.307 0 0 0 .094-.125.436.436 0 0 0 .059-.2.095.095 0 0 0-.026-.063c-.052-.062-.2-.152-.518-.209a3.881 3.881 0 0 0-.612-.053zM8.078 5.8a6.7 6.7 0 0 0 .2-.828c.031-.188.043-.343.038-.465a.613.613 0 0 0-.032-.198.517.517 0 0 0-.145.04c-.087.035-.158.106-.196.283-.04.192-.03.469.046.822.024.111.054.227.09.346z"/></svg>
			</a>';

		$pos     = strpos( $content, '</div>' );
		$content = substr( $content, 0, $pos ) . $extra . substr( $content, $pos );

		return $content;
	},
	10,
	2
);


function mk_get_star_ratings_output() {
	$nonce = wp_create_nonce( 'user_star_rating' );

	$header_enable       = get_field( 'kitces_star_ratings_header' );
	$all_enable          = get_field( 'kitces_enable_for_all_posts', 'options' );
	$enable              = $all_enable ? $all_enable : $header_enable;
	$star_ratings_output = null;

	if ( $enable ) {
		ob_start();
		?>
		<div class="star-ratings__wrapper-header">
			<div id="" class="star-ratings star-ratings__header">
				<h3 class="star-ratings__question"></h3>
				<div class="star-ratings__stars" data-nonce="<?php echo esc_attr( $nonce ); ?>" data-version="">
				</div>
				<div class="star-ratings__message">
					<p class="hide"></p>
				</div>
			</div>
		</div>
		<?php
		$star_ratings_output = ob_get_contents();
		ob_end_clean();
	}
	return $star_ratings_output;
}

add_action( 'genesis_entry_content', 'cgd_jetpack_float', 1 );
function cgd_jetpack_float() {
	if ( function_exists( 'shared_counts' ) && is_singular( 'post' ) ) {
		echo '<div class="kitces-sharing is-floating">';
		echo '<div class="social-links-wrapper">';
		echo do_shortcode( '[shared_counts]' );
		echo '</div>';
		echo "<div class='kitces-sharing-close' id='kitces-sharing-close'><svg xmlns='http://www.w3.org/2000/svg' class='h-5 w-5' viewBox='0 0 20 20' fill='currentColor'><path fill-rule='evenodd' d='M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z' clip-rule='evenodd' /></svg></div>";
		echo '</div>';
	}
}

function cgd_add_taxonomies_to_pages() {
	register_taxonomy_for_object_type( 'category', 'page' );
}

add_action( 'init', 'cgd_add_taxonomies_to_pages' );

add_action( 'init', 'customRSS' );
function customRSS() {
		add_feed( 'events', 'customRSSFunc' );
}

function customRSSFunc() {
		get_template_part( 'rss', 'events' );
}

function kitces_content_feed( $feed_type = null ) {
	if ( ! $feed_type ) {
		$feed_type = get_default_feed();
	}
	global $more;
	$more_restore = $more;
	$more         = 0;
	$content      = apply_filters( 'the_content', get_the_content() );
	$more         = $more_restore;
	$content      = str_replace( ']]>', ']]&gt;', $content );
	return $content;
}
add_filter( 'the_content_feed', 'kitces_content_feed' );

add_action( 'wp_footer', 'quantcast' );
function quantcast() {
	?>
	<!-- Quantcast Tag -->
	<script type="text/javascript">
	var _qevents = _qevents || [];

	(function() {
	var elem = document.createElement('script');
	elem.src = (document.location.protocol == "https:" ? "https://secure" : "http://edge") + ".quantserve.com/quant.js";
	elem.async = true;
	elem.type = "text/javascript";
	var scpt = document.getElementsByTagName('script')[0];
	scpt.parentNode.insertBefore(elem, scpt);
	})();

	_qevents.push({
	qacct:"p-6fB0yHbqHRvgK"
	});
	</script>

	<noscript>
	<div style="display:none;">
	<img src="//pixel.quantserve.com/pixel/p-6fB0yHbqHRvgK.gif" border="0" height="1" width="1" alt="Quantcast"/>
	</div>
	</noscript>
	<!-- End Quantcast tag -->
	<?php
}

add_filter( 'jetpack_sharing_display_text', 'modify_print_button_text', 10, 4 );

function modify_print_button_text( $text, $object, $id, $args ) {
	if ( $object->shortname == 'print' ) {
		return 'Print / PDF';
	}

	return $text;
}

// Move Related Posts
function jetpackme_remove_rp() {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		$jprp     = Jetpack_RelatedPosts::init();
		$callback = array( $jprp, 'filter_add_target_to_dom' );
		remove_filter( 'the_content', $callback, 40 );
	}
}
add_filter( 'wp', 'jetpackme_remove_rp', 20 );

add_action( 'genesis_entry_footer', 'cgd_jetpack_related_posts', 15 );
function cgd_jetpack_related_posts() {
	if ( class_exists( 'Jetpack_RelatedPosts' ) ) {
		echo do_shortcode( '[jetpack-related-posts]' );
	}
}

add_filter( 'shared_count', 'kitces_add_newsharecount', 10, 2 );

function kitces_add_newsharecount( $count, $url ) {
	$result = wp_remote_get( 'http://public.newsharecounts.com/count.json?url=' . $url );

	if ( is_wp_error( $result ) ) {
		return $count;
	}

	$result = json_decode( $result['body'], true );

	if ( isset( $result['count'] ) && is_numeric( $result['count'] ) ) {
		$count = $count + $result['count'];
	}

	return $count;
}

add_filter( 'sharing_permalink', 'kitces_share_link_utm', 10, 3 );

function kitces_share_link_utm( $permalink, $post_id, $sharing_id ) {
	$ga_params = array(
		'utm_source'   => $sharing_id,
		'utm_medium'   => 'Social',
		'utm_campaign' => 'ShareBar',
	);

	return add_query_arg( $ga_params, $permalink );
}


add_action( 'genesis_before', 'cgd_google_tag_manager_script' );

function cgd_google_tag_manager_script() {
	?>
<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-TQR2ZS"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TQR2ZS');</script>
<!-- End Google Tag Manager -->
	<?php
}

add_action( 'genesis_meta', 'kitces_meta_referrer_tag' );

function kitces_meta_referrer_tag() {
	echo '<meta name="referrer" content="always">';
}

add_action( 'add_meta_boxes', 'kitces_remove_pagefrog_preview_from_pages', 100 );

function kitces_remove_pagefrog_preview_from_pages() {
	remove_meta_box( 'pagefrog-preview-meta-box', 'page', 'side' );
}

add_filter( 'script_loader_src', 'kitces_asset_src' );
add_filter( 'style_loader_src', 'kitces_asset_src' );

function kitces_asset_src( $url ) {
	if ( stripos( $url, 'kitces.com' ) === false ) {
		return $url;
	}

	return add_query_arg( array( 'ctv' => CHILD_THEME_VERSION ), $url );
}

// Remove unused css files
add_action( 'wp_enqueue_scripts', 'kitces_deregister_styles', 1000 );
add_action( 'wp_head', 'kitces_deregister_styles', 1000 );
function kitces_deregister_styles() {
	if ( ! is_admin_bar_showing() ) {
		// WordPress native file
		wp_deregister_style( 'dashicons' );
	}

	// Feedly Insights file
	wp_deregister_style( 'fi_buttons' );
}

// Add the featured image to all archive pages and the search results
add_action( 'genesis_entry_content', 'objectiv_add_featured_image_archives', 0 );
function objectiv_add_featured_image_archives() {
	if ( is_archive() || is_search() ) {
		$thumb_id        = get_post_thumbnail_id();
		$thumb_url_array = wp_get_attachment_image_src( $thumb_id, 'thumbnail', true );
		$thumb_url       = $thumb_url_array[0];
		$alt             = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );

		if ( has_post_thumbnail() ) {
			echo '<a href="' . get_permalink() . '" class="post-thumbnail"><img src="' . $thumb_url . '" alt="' . $alt . '"></a>';
		}
	}
}

// FeedBlitz Override for <link> tags
add_filter(
	'feed_link',
	function( $url ) {
		$url = 'http://feeds.kitces.com/KitcesNerdsEyeView';

		return $url;
	}
);

// Redirect feed unless a secret string is present
add_action( 'template_redirect', 'kitces_restrict_feed_access', 1 );

function kitces_restrict_feed_access() {
	global $feed;

	if ( ! is_feed() ) {
		return;
	}

	if ( $feed == 'comments-rss2' ) {
		return;
	}

	if ( empty( $_GET['secret'] ) || $_GET['secret'] != 'ciendm5mshzuwa' ) {
		wp_redirect( 'http://feeds.feedblitz.com/KitcesNerdsEyeView' );
		exit();
	}
}

// remove_action( 'genesis_upgrade', 'genesis_upgrade_redirect' );

// Enable shortcodes in text widgets
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_title', 'do_shortcode' );

/**
 * Hide editor for specific page templates.
 */
add_action( 'admin_init', 'objectiv_hide_editor' );
function objectiv_hide_editor() {
	// Get the Post ID.
	$post    = isset( $_GET['post'] ) ? $_GET['post'] : '';
	$post_id = $post ? $post : ( isset( $_POST['post_ID'] ) ? $_POST['post_ID'] : '' );

	if ( ! isset( $post_id ) ) {
		return;
	}

	// Get the name of the Page Template file.
	$template_file = get_post_meta( $post_id, '_wp_page_template', true );

	// Add a template file to hide the editor on that template.
	$template_file_array = array(
		'template-members-2.php',
		'template-consulting-2.0.php',
		'template-speaking-schedule.php',
		'template-product-landing.php',
		'template-speaking-2.php',
		'template-podcast.php',
		'template-conferences-list.php',
		'template-scholarships-list.php',
		'template-team.php',
		'template-kitces-events.php',
	);

	if ( in_array( $template_file, $template_file_array ) ) {
		remove_post_type_support( 'page', 'editor' );
	}
}

function ovdump( $data ) {
	print( '<pre>' . print_r( $data, true ) . '</pre>' );
}

function obj_svg( $url = null, $class = false, $description = '' ) {
	$desc = ! empty( $description ) ? ' data-description="' . $description . '"' : '';

	if ( ! empty( $url ) ) {
		return "<img class='style-svg $class' src='$url'$desc>";
	} else {
		return '';
	}
}

function obj_display_single_cci_quiz() {
	$cc_cta                = get_field( 'quiz_cta_text_top_of_each_quiz', 'options' );
	$parents_to_display_on = get_field( 'quiz_cta_display_on_children_of', 'options' );
	$parent_id             = wp_get_post_parent_id( get_the_ID() );

	$cci       = get_field( 'course_catalog_item', get_the_ID() );
	$cci_title = get_the_title( $cci );
	global $course_catalog_post;
	$course_catalog_post = get_post( $cci );

	if ( ! empty( $cci ) ) {
		echo ( '<div class="accordion-block quiz-cci">' );
		cgd_cci_row( $cci, $cci_title );
		echo ( '</div>' );
		wp_reset_query();
	}

	if ( ! empty( $parent_id ) && in_array( $parent_id, $parents_to_display_on ) && ! empty( $cc_cta ) ) {
		echo ( '<div class="course-catalog-cta">' );
		echo( $cc_cta );
		echo ( '</div>' );
		if ( empty( $cci ) ) {
			echo ( '<hr>' );
		}
	}

	if ( ! empty( $cci ) ) {
		echo( '<hr>' );
	}
}
add_action( 'genesis_entry_content', 'obj_display_single_cci_quiz', 0 );

function obj_display_take_quiz_button() {
	$parents_to_display_on = get_field( 'quiz_cta_display_on_children_of', 'options' );
	$parent_id             = wp_get_post_parent_id( get_the_ID() );

	if ( ! empty( $parent_id ) && in_array( $parent_id, $parents_to_display_on ) ) {
		echo ( '<button class="button take-quiz-btn tqtop">Take Quiz</button>' );
	}

}
add_action( 'genesis_entry_content', 'obj_display_take_quiz_button', 100 );

// Groove Widget Code Logic
function objectiv_display_groove_widget() {
	$faq_script          = get_field( 'faq_script', 'options' );
	$pages_to_display_on = get_field( 'faq_button_pages_to_display_on', 'options' );
	$current_page        = get_the_ID();
	$display_faq_btn     = false;

	// If the current page has an ancestor (parent or parent of a parent) on display list we want to display it
	if ( is_array( $pages_to_display_on ) && ! empty( $pages_to_display_on ) ) {
		foreach ( $pages_to_display_on as $page_id ) {
			if ( ! $display_faq_btn && in_array( $page_id, get_post_ancestors( $current_page ) ) ) {
				$display_faq_btn = true;
			}
		}
	}

	// If the current page is in the pages to display list we want to display it
	if ( in_array( $current_page, $pages_to_display_on ) ) {
		$display_faq_btn = true;
	}

	if ( $display_faq_btn && ! empty( $faq_script ) ) {
		echo( $faq_script );
	}

}
add_action( 'wp_footer', 'objectiv_display_groove_widget' );

/**
 * Retrieves a string that can be used as an id on an html element
 *
 * @author Eldon Yoder
 * @link http://objectiv.co/
 */

function obj_id_from_string( $string = null, $rand = true, $num = 5 ) {
	if ( ! empty( $string ) ) {
		if ( $rand ) {
			$whoa = substr( md5( microtime() ), rand( 0, 26 ), $num );
			return strtolower( preg_replace( '/[^a-z]+/i', '_', $string ) . $whoa );
		} else {
			return strtolower( preg_replace( '/[^a-z]+/i', '_', $string ) );
		}
	} else {
		return null;
	}
}

// EDD Tweaks
// define the edd_checkout_personal_info_text callback
function obj_filter_edd_checkout_personal_info_text( $esc_html ) {
	$esc_html = 'Your Information';
	return $esc_html;
};
// add the filter
add_filter( 'edd_checkout_personal_info_text', 'obj_filter_edd_checkout_personal_info_text', 10, 1 );

function obj_edd_purchase_form_required_fields( $required_fields ) {
	$required_fields['edd_last'] = array(
		'error_id'      => 'invalid_last_name',
		'error_message' => __( 'Please enter your last name.', 'edd' ),
	);
	return $required_fields;
}
add_filter( 'edd_purchase_form_required_fields', 'obj_edd_purchase_form_required_fields' );

// define the edd_payment_receipt_before callback
function obj_edd_before_reciept_text( $payment, $edd_receipt_args ) {
	$user_info = edd_get_payment_meta_user_info( $payment->ID );
	$name      = $user_info['first_name'];

	if ( ! empty( $name ) ) {
		echo "<p>Thanks for purchasing a research paper {$name}!<p>";
		echo "<p>You can download the paper using the link below, and you'll also recieve an email with that link.<p>";
		echo '<hr>';
		echo '<h3>Your Order Details<h3>';
	} else {
		echo '<p>Thanks for purchasing a research paper!<p>';
		echo "<p>You can download the paper using the link below, and you'll also recieve an email with that link.<p>";
		echo '<hr>';
		echo '<h3>Your Order Details<h3>';
	}
};
// add the action
add_action( 'edd_payment_receipt_before', 'obj_edd_before_reciept_text', 10, 2 );

// Disable confirmation anchor for poll
add_filter( 'gform_confirmation_anchor_105', '__return_false' );


add_filter( 'wp_dropdown_users', 'obj_dropdown_users', 10, 1 );
function obj_dropdown_users( $output ) {
	if ( function_exists( 'get_current_screen' ) ) {
		$screen = get_current_screen();
		if ( is_admin() && $screen->post_type === 'post' ) {
			global $post;

			// Get users that are editors, authors or admins
			$args  = array(
				'role__in' => array(
					'editor',
					'author',
					'administrator',
				),
			);
			$users = get_users( $args );

			// Set up arrays for filtering
			$kitces_team = array();
			$non_team    = array();

			// Filter users
			foreach ( $users as $user ) {
				$display_name = $user->data->display_name;
				$author_name  = get_field( 'author_name', 'user_' . $user->ID );
				$on_team      = get_field( 'author_kitces_team_member', 'user_' . $user->ID );

				if ( ! empty( $author_name ) ) {
					$display_name = $author_name;
				}

				$clean_user = array(
					$user->ID => $display_name,
				);

				if ( $on_team ) {
					$kitces_team[ $user->ID ] = $display_name . ' - Kitces Team';
				} else {
					$non_team[ $user->ID ] = $display_name;
				}
			}

			// Sort resulting arrays
			asort( $kitces_team );
			asort( $non_team );

			$output = '<select id="post_author_override" name="post_author_override" class="">';

			foreach ( $kitces_team as $user_id => $user_name ) {
				$sel     = ( $post->post_author == $user_id ) ? "selected='selected'" : '';
				$output .= '<option value="' . $user_id . '"' . $sel . '>' . $user_name . '</option>';
			}

			foreach ( $non_team as $user_id => $user_name ) {
				$sel     = ( $post->post_author == $user_id ) ? "selected='selected'" : '';
				$output .= '<option value="' . $user_id . '"' . $sel . '>' . $user_name . '</option>';
			}

			$output .= '</select>';

			return $output;
		}
	}
}


/**
 * Deal with the custom RSS templates.
 */
function kitces_custom_rss() {
	if ( $_GET['day_filter'] === 'mwf' ) {
		get_template_part( 'feed', 'mwf' );
	} elseif ( $_GET['day_filter'] === 'tth' ) {
		get_template_part( 'feed', 'tth' );
	} elseif ( $_GET['day_filter'] === 'mw' ) {
		get_template_part( 'feed', 'mw' );
	} elseif ( $_GET['day_filter'] === 'allrecent' ) {
		get_template_part( 'feed', 'allrecent' );
	} else {
		get_template_part( 'feed', 'f' );
	}
}

if ( ! empty( $_GET['day_filter'] ) ) {
	remove_all_actions( 'do_feed_rss2' );
	add_action( 'do_feed_rss2', 'kitces_custom_rss', 10, 1 );
}


// User Quiz Certification Page.
new UserQuizCertification();

// User Access.
new UserAccess();

// Taxonomies.
new Taxonomies();

// Advanced Sesrch.
new AdvancedSearch();

// Member Role Cookies
new MemberRoleCookies();

// Search Query
new SearchQuery();

// GUID Meta Tag
new GuidMetaTag();

// Convert {entry:date_created} merge tag output to EST timezone
add_filter( 'gform_merge_tag_data', 'kitces_adjust_gf_entry_date', 10, 1 );

function kitces_adjust_gf_entry_date( $data ) {
	$timezone = new DateTimeZone( 'America/New_York' );
	$new_date = new DateTime( $data['entry']['date_created'] );
	$new_date->setTimezone( $timezone );

	$data['entry']['date_created'] = $new_date->format( 'm/d/Y' );
	$data['entry']['time_created'] = $new_date->format( 'g:ia' ) . ' Eastern Time Zone';

	return $data;
}

function mk_search_filter( $query ) {
	$admin = current_user_can( 'manage_options' );

	if ( $query->is_search && ! $admin ) {

		$ex_args = array(
			'posts_per_page'   => -1,
			'post_type'        => array( 'post', 'page' ),
			'fields'           => 'ids',
			'suppress_filters' => true,
			'meta_query'       => array(
				'relation' => 'OR',
				array(
					'key'     => 'remove_from_search',
					'value'   => '1',
					'compare' => '=',
				),
				array(
					'key'     => '_wp_page_template',
					'value'   => 'template-conference-landing-page.php',
					'compare' => '=',
				),
			),
		);

		$excluders = new WP_Query( $ex_args );
		$excluders = $excluders->posts;

		$query->set( 'post__not_in', $excluders );
	}
	return $query;
}
add_filter( 'pre_get_posts', 'mk_search_filter' );

// Enable shortcodes in all in one seo titles
add_filter( 'aioseo_title', 'do_shortcode' );

function mk_filter_pages_sitemap( $sitemap_data, $sitemap_type, $page_number, $options ) {
	if ( 'page' === $sitemap_type ) {
		$remove_with_children = array(
			677,
			2735,
		);
		$excluded_permalinks  = array();
		$clean_sitemap_data   = array();

		foreach ( $remove_with_children as $id_1 ) {
			array_push( $excluded_permalinks, get_permalink( $id_1 ) ); // Exclude the parent from the sitemap

			$children_1 = mk_get_child_pages_posts( $id_1 );

			if ( ! empty( $children_1 ) ) {
				foreach ( $children_1 as $child_1 ) {
					array_push( $excluded_permalinks, get_permalink( $child_1->ID ) ); // Exclude the children from the sitemap

					$children_2 = mk_get_child_pages_posts( $child_1->ID );

					if ( ! empty( $children_2 ) ) {
						foreach ( $children_2 as $child_2 ) {
							array_push( $excluded_permalinks, get_permalink( $child_2->ID ) ); // Exclude the children from the sitemap

							$children_3 = mk_get_child_pages_posts( $child_2->ID );

							if ( ! empty( $children_3 ) ) {
								foreach ( $children_3 as $child_3 ) {
									array_push( $excluded_permalinks, get_permalink( $child_3->ID ) ); // Exclude the children from the sitemap
								}
							}
						}
					}
				}
			}
		}

		foreach ( $sitemap_data as $sm_page ) {
			if ( ! in_array( $sm_page['loc'], $excluded_permalinks ) ) {
				array_push( $clean_sitemap_data, $sm_page );
			}
		}

		$sitemap_data = $clean_sitemap_data;
	}

	return $sitemap_data;

}
add_filter( 'aiosp_sitemap_data', 'mk_filter_pages_sitemap', 10, 4 );

function mk_get_child_pages_posts( $id ) {
	$child_args = array(
		'post_type'      => 'any',
		'posts_per_page' => -1,
		'post_parent'    => $id,
	);

	return get_posts( $child_args );
}

/**
 * @param bool $post
 *
 * @return bool
 */
function kitces_is_quiz_page( $post = false ) {
	global $CGD_CECredits;

	if ( ! $post ) {
		global $post;
	}

	if ( ! in_array( $post->post_parent, kitces_get_quiz_parent_pages(), true ) ) {
		return false;
	}

	$matches = false;
	preg_match( '@id="(\d+)"@', $post->post_content, $matches );

	if ( ! empty( $matches ) && $CGD_CECredits->form_is_quiz( array( 'id' => $matches[1] ) ) ) { // phpcs:ignore
		return true;
	}

	return false;
}

/**
 * @return int[]
 */
function kitces_get_quiz_parent_pages() {
	return array(
		761,  // CE Quizzes.
		2735, // Webinars.
		6719, // Nerd's Eye View Blog CE Quizzes.
	);
}

/**
 * Kill the AIOSEO sitemap update
 *
 * Runs on earlier hooks post_updated and publish_post
 *
 * @author Jason Witt
 *
 * @return void.
 */
function remove_aiosso_sitemap_update() {
	global $wp_filter;

	$transition_post_status_hook = $wp_filter['transition_post_status'];

	if ( $transition_post_status_hook[10] ) {
		foreach ( $transition_post_status_hook[10] as $key => $callback ) {
			if ( false !== stripos( $key, 'update_sitemap_from_posts' ) ) {
				$sitemap_update = $callback['function'][0];
			}
		}
	}

	// Kill AIOSEO sitemap update on post status change.
	if ( class_exists( 'All_in_One_SEO_Pack_Sitemap' ) && ! empty( $sitemap_update ) ) {
		remove_action( 'transition_post_status', array( $sitemap_update, 'update_sitemap_from_posts' ) );
	}
}
add_action( 'transition_post_status', 'remove_aiosso_sitemap_update', 0 );

/**
 * Wrapper for ACF get_field
 *
 * @author Jason Witt
 */
function mk_acf_get_field( $selector = null, $post_id = null, $format_value = true, $use_acf = false ) {
	$result = null;

	if ( $use_acf ) {
		// Allow for just using ACF.
		$result = function_exists( 'get_field' ) ? get_field( $selector, $post_id, $format_value ) : '';
	} else {

		// Grab options setting if that is what is set, otherwise get post meta.
		if ( $post_id === 'options' ) {
			$result = get_option( 'options_' . $selector );
		} else {
			$result = get_post_meta( $post_id, $selector, true );
		}

		// Fall back to ACF field selector if we don't have anything.
		if ( empty( $result ) ) {
			$result = function_exists( 'get_field' ) ? get_field( $selector, $post_id, $format_value ) : '';
		}
	}

	if ( empty( $result ) ) {
		$result = false;
	}

	return $result;
}

if ( ! function_exists( 'get_field' ) ) {
	function get_field( $selector = null, $post_id = null, $format_value = true, $use_acf = false ) {
		return mk_acf_get_field( $selector, $post_id, $format_value, $use_acf );
	}
}

// Disabled to use AL1SEO rich snippets.
add_filter( 'genesis_disable_microdata', '__return_true' );

// Add a bit of custom css for admin area
add_action( 'admin_head', 'tweak_admin_css' );
function tweak_admin_css() {
	echo '<style>
    #adminmenu .wp-menu-image img {
        max-width: 20px;
    }
  </style>';
}


/**
 * Create user roles if they don't already exist.
 */
function mk_add_user_roles() {
	$guest_author_role = $GLOBALS['wp_roles']->is_role( 'guest_author' );
	if ( empty( $guest_author_role ) ) {
		add_role( 'guest_author', 'Guest Author', get_role( 'subscriber' )->capabilities );
	}

	$reader_role = $GLOBALS['wp_roles']->is_role( 'reader' );
	if ( empty( $reader_role ) ) {
		add_role( 'reader', 'Reader', get_role( 'subscriber' )->capabilities );
	}
}

add_action( 'init', 'mk_add_user_roles' );

function mk_ie_modal_content() {

	$is_admin = is_user_logged_in() && current_user_can( 'administrator' );

	if ( ! isset( $_COOKIE['ieModalSessionClosed'] ) && ! $is_admin ) {
		?>
		<a class="hidden ie-confirm-link" id="mk-ie-modal-link" href="#mk-ie-modal-content"></a>
		<div class="hidden" id="mk-ie-modal-content">
			<div class="the-ie-content norm-list last-child-margin-bottom-0">
				<p>This browser is no longer supported by Microsoft and may have performance, security, or missing functionality issues. For the best experience using Kitces.com we recommend using one of the following browsers.</p>
				<ul>
					<li><a target="_blank" href="https://www.microsoft.com/en-us/edge">Microsoft Edge</a></li>
					<li><a target="_blank" href="https://www.mozilla.org/en-US/firefox/new/">Mozilla Firefox</a></li>
					<li><a target="_blank" href="https://www.google.com/chrome/">Google Chrome</a></li>
					<li><a target="_blank" href="https://www.apple.com/safari/">Safari for Mac</a></li>
				</ul>
			</div>
		</div>
		<?php
	}
}
add_action( 'genesis_after', 'mk_ie_modal_content' );

// Format quotes from the WP WYSIWYG
remove_filter( 'the_content', 'wptexturize' );

add_filter( 'manage_users_columns', 'mk_add_user_id_column' );
function mk_add_user_id_column( $columns ) {
	$columns['user_id'] = 'User ID';
	return $columns;
}

add_action( 'manage_users_custom_column', 'mk_show_user_id_column_content', 10, 3 );
function mk_show_user_id_column_content( $value, $column_name, $user_id ) {
	$user = get_userdata( $user_id );
	if ( 'user_id' == $column_name ) {
		return $user_id;
	}
	return $value;
}

$acf_ac_fields = array(
	'ac_contact_id',
	'ac_cfp_ce_number',
	'ac_cpa_ce_number',
	'ac_imca_ce_number',
	'ac_american_college_id',
	'ac_iar_ce_number',
	'ac_expiration_date',
);

function acf_read_only( $field ) {
	$field['readonly'] = 1;
	return $field;
}

foreach ( $acf_ac_fields as $acf_ac_field ) {
	add_filter( "acf/load_field/name=$acf_ac_field", 'acf_read_only' );
}

add_action( 'after_setup_theme', 'remove_admin_bar' );
function remove_admin_bar() {
	if ( ! current_user_can( 'administrator' ) && ! is_admin() ) {
		show_admin_bar( false );
	}
}

/**
 * Add quiz timer to quizzes above the submit button
 */
function form_submit_button( $button, $form ) {
	global $post;

	$quizzes = array(
		6719,
		2735,
	);

	$quiz_timer = '';
	if ( is_object( $post ) && in_array( $post->post_parent, $quizzes, true ) ) {
		$quiz_timer = '<div id="quiz-timer-bottom" class="quiz-timer quiz-timer-bottom">The time and date of completion of this quiz will be approximately:<br/><strong><span class="quiz-timer-time"></span> (Eastern Time Zone)</strong><br/><strong><span class="quiz-timer-date"></span></strong></div>';
	}

	return $quiz_timer . $button;
}
add_filter( 'gform_submit_button', 'form_submit_button', 999, 2 );


function upvotysso_custom() {
	$current_user = wp_get_current_user();
	if ( $current_user &&
	$current_user->ID &&
	$current_user->display_name &&
	$current_user->user_email ) {
		?>
			<div data-upvoty></div>
			<script type='text/javascript' src='https://kitces.upvoty.com/javascript/upvoty.embed.js'></script>
			<script type='text/javascript'>
			upvoty.init('identify', {
				user: {
					id: <?php echo json_encode( $current_user->ID ); ?>,
					name: <?php echo json_encode( $current_user->display_name ); ?>,
					email: <?php echo json_encode( $current_user->user_email ); ?>,
				},
				baseUrl: 'kitces.upvoty.com',
				publicKey: '89c578b5a0a6f146bec65557b96a608f',
			});
			</script>
		<?php
	}
}
add_action( 'wp_footer', 'upvotysso_custom' );

function admin_subsriber_redirect() {
	$allowed_roles = array( 'editor', 'administrator', 'author', 'contributor' );
	$user          = wp_get_current_user();

	if ( is_admin() && ! array_intersect( $allowed_roles, $user->roles ) && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
		wp_redirect( home_url( '/member/' ) );
		exit;
	}
}

add_action( 'admin_init', 'admin_subsriber_redirect' );

// Function to show modal (CE number empty)
function kitces_modal_ce_number_empty() {
	?>
	<a href="#kitces_update_ce_modaal" id="kitces_update_ce_modaal_link" style="display:none" aria-hidden="true">Open Update CE Modal</a>
	<div id="kitces_update_ce_modaal" style="display:none;">
	<div class="modalCe">
		<p class="modaal-title">CE numbers not found!</p>
		<p class="modaal-text"> CE numbers are required for Kitces to report your credits. Would you like to add your CE numbers now? </p>
		<div class="modaal-cont">
			<span id="close-modaal" class="btn-ce-no">No, thank you.</span>
			<a href="https://kitces.com/member/my-account/"><button type="button" class="btn-ce-text"> Yes, take me there!</button></a>
		</div>
	</div>
	</div>
	<?php
}
add_action( 'genesis_after_header', 'kitces_modal_ce_number_empty' );
?>
<?php
//* Function send message to user after sharing article via email 
add_action( 'wp_head', function () { ?>
    <script>
        function checkEmailEmpty() {
        var recipient = document.getElementById("shared-counts-modal-recipient");
        var name = document.getElementById("shared-counts-modal-name");
        var email = document.getElementById("shared-counts-modal-email");
         if((recipient.value=="") || (name.value=="") || (email.value=="")){ 
                document.getElementById('shared-counts-modal-submit').disabled = true; 
                //* Disable the button if the recipient, name, or email fields are empty
            } else { 
                document.getElementById('shared-counts-modal-submit').disabled = false;
            }
        }
		//* Show alert to user after sharing article
        function sentEmailMessage(){
            alert("Thank you for sharing. E-mail sent!");
        }
    </script>
    <?php } );
