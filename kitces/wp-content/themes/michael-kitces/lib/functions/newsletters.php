<?php
register_post_type('newsletter',
array(	
	'label' => 'Newsletters',
	'public' => true,
	'show_ui' => true,
	'show_in_menu' => true,
	'show_in_nav_menus' => true,
	'has_archive' => false,
	'capability_type' => 'post',
	'hierarchical' => false,
	'rewrite' => array('slug' => 'newsletter'),
	'query_var' => true,
	'supports' => array(
		'title',),
	'labels' => array (
		'name' => 'Newsletters',
		'singular_name' => 'Newsletter',
		'menu_name' => 'Newsletters',
		'add_new' => 'Add Newsletter',
		'add_new_item' => 'Add New Newsletter',
		'edit' => 'Edit',
		'edit_item' => 'Edit Newsletter',
		'new_item' => 'New Newsletter',
		'view' => 'View Newsletter',
		'view_item' => 'View Newsletter',
		'search_items' => 'Search Newsletters',
		'not_found' => 'No Newsletters Found',
		'not_found_in_trash' => 'No Newsletters Found in Trash',
		'parent' => 'Parent Newsletter'
	),
) );


/* Define the custom box */

// WP 3.0+
add_action('add_meta_boxes', 'jh_nlforms_meta_box');

/* Do something with the data entered */
add_action('save_post', 'jh_nlforms_save_postdata');

/* Adds a box to the main column on the Post and Page edit screens */
function jh_nlforms_meta_box() {
    add_meta_box( 'jh_nlforms_sectionid', __( 'Newsletter Embed Code', 'jh_nlforms_textdomain' ), 'jh_nlforms_inner_custom_box','newsletter', 'normal');
}

/* Prints the box content */
function jh_nlforms_inner_custom_box() {

// Use nonce for verification
	wp_nonce_field( plugin_basename(__FILE__), 'jh_nlforms_noncename' );
	global $post;
	$nl_embed = get_post_meta($post->ID, 'nl_embed', true);
	// The actual fields for data entry ?>
<table border="0" cellspacing="5" cellpadding="5" width="100%">
	<tr>
	<td>
		<label for="nl_embed">Newsletter Embed Form</label><br>
		<textarea rows="5" cols="60" name="nl_embed" id="nl_embed" style="width: 100%"><?php echo $nl_embed; ?></textarea>
		<br><br>
		<strong>Use Shortcode: </strong> <code>[newsletter id="<?php echo $post->ID; ?>"]</code>
	</td>
	</tr>
</table>
  
	
<?php
}

/* When the post is saved, saves our custom data */
function jh_nlforms_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['jh_nlforms_noncename'], plugin_basename(__FILE__) ) )
      return $post_id;
  // verify if this is an auto save routine. 
  // If it is our form has not been submitted, so we dont want to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
      return $post_id;

  
  // Check permissions
  if ( 'newsletter' == $_POST['post_type'] ) 
  {
    if ( !current_user_can( 'edit_page', $post_id ) )
        return $post_id;
  }
  else
  {
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data

	$nl_embed = $_POST['nl_embed'];

  // update the data
	update_post_meta($post_id, 'nl_embed', $nl_embed);
}

// [newsletter id='1551' text="Signup for the newsletter and be a beast!!"]
function jh_newsletter_function($atts) {
	extract(shortcode_atts(array(
		"text" => "",
		"id" => ""
    ), $atts));
	// if no ID is passed. the FIRST Newsletter in the menu is used.
	if (strlen($id) == 0) {
		$nls = get_posts('numberposts=1&orderby=menu_order&order=ASC&post_type=newsletter&post_status=publish');
		foreach ($nls as $nl) {
			$id = $nl->ID;
		}
	}
	// creating a variable retcont to be used to return content
		$retcont = '';
		$retcont .= '<div class="nlsignup_content wide">';
		if (!empty($text)) {
			$retcont .= '<p>'.$text.'</p>';
		}
		$retcont .= get_post_meta($id, 'nl_embed', true) . '</div>';
		return $retcont;
	}
	add_shortcode("newsletter", "jh_newsletter_function");
	

// initializes the widget on WordPress Load
add_action('widgets_init', 'nlsignup_init_widget');

// ********
// This is a widget for the newsletter signup

// Should be called above from "add_action"
function nlsignup_init_widget() {
	register_widget( 'NL_Signup_Widget' );
}

// new class to extend WP_Widget function
class NL_Signup_Widget extends WP_Widget {
	/** Widget setup.  */
	function NL_Signup_Widget() {
		/* Widget settings. */
		$widget_ops = array(
			'classname' => 'nlsignup_widget',
			'description' => __('Widget for Newsletter Signup', 'nlsignup_widget') );

		/* Widget control settings. */
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'nl-signup-widget' );

		/* Create the widget. */
		$this->WP_Widget( 'nl-signup-widget', __('Newsletter Signup Widget', 'Options'), $widget_ops, $control_ops );
	}
	/**
	* How to display the widget on the screen. */
	function widget( $args, $instance ) {
		extract( $args );
		$nlID = $instance['newsletter'];
		$title = apply_filters('widget_title', $instance['title'] );

		/* Before widget (defined by themes). */
		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;
		/* Display name from widget settings if one was input. */

		// Settings from the widget
		// use get_post to get the ID from the very specific Newsletter
		$nls = $nlID;
		$nls = get_post($nls); 
		$id = $nls->ID;
	?>
		<div class="newsletter-widget">
			<?php echo get_post_meta($id, 'nl_embed', true); ?>
		</div>
		<?php
		/* After widget (defined by themes). */
		echo $after_widget;
	}

	/**
	 * Update the widget settings.
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['newsletter'] = $new_instance['newsletter'];
		return $instance;
	}

/**
 * Displays the widget settings controls on the widget panel.
 * Make use of the get_field_id() and get_field_name() function
 * when creating your form elements. This handles the confusing stuff.
*/
	function form($instance) {
		$defaults = array( 
			'title' => __('Newsletter Signup', 'nlsignup_widget'),
			'newsletter' => 0
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'nlsignup_widget'); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" class="widefat" />
		</p>
		<p>
			<?php $newsletters = get_posts('numberposts=-1&orderby=menu_order&order=ASC&post_type=newsletter&post_status=publish');
			if (!empty($newsletters)) { ?>
			<select name="<?php echo $this->get_field_name( 'newsletter' ); ?>" id="<?php echo $this->get_field_id( 'newsletter' ); ?>">
				<option value="0">Select a newsletter embed code</option>
			<?php 
			foreach ($newsletters as $newsletter) { ?>
				<option value="<?php echo $newsletter->ID; ?>" <?php if ($newsletter->ID == $instance['newsletter']): ?>selected="selected"<?php endif; ?>><?php echo $newsletter->post_title; ?></option>
			<?php }	?>
			</select>
			<?php
			}
			?>
		</p>
		<p>This widget pulls the content from the <a href="<?php bloginfo('url'); ?>/wp-admin/admin.php?page=functions">Newsletter Embed</a> code.</p>
	<?php
	}
}