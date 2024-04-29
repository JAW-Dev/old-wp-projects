<?php
/**
 * Handles all Advisor News functionality
 */
class Kitces_Advisor_News {

	/**
	 * Post type
	 *
	 * @var string
	 */
	public static $post_type = 'advisor-news';

	/**
	 * Get an instance of this class
	 */
	public static function get_instance() {
		static $instance = null;
		if ( null === $instance ) {
			$instance = new static();
			$instance->setup_actions();
			$instance->setup_filters();
		}
		return $instance;
	}

	/**
	 * Hook in to WordPress via actions
	 */
	public function setup_actions() {
		add_action( 'init', array( $this, 'action_init' ) );
		add_action( 'acf/init', array( $this, 'action_acf_init' ) );
		add_action( 'template_redirect', array( $this, 'action_template_redirect' ) );
	}

	/**
	 * Hook in to WordPress via filters
	 */
	public function setup_filters() {
		add_filter( 'acf/update_value/key=field_advisor_news_commentary', array( $this, 'filter_update_advisor_news_commentary_field' ), 10, 2 );
		add_filter( 'acf/update_value/key=field_advisor_news_url', array( $this, 'filter_update_advisor_news_url' ), 10, 2 );
	}

	/**
	 * Initialize post type and taxonomies
	 */
	public function action_init() {
		$args = array(
			'labels'              => Kitces_Helpers::generate_post_type_labels( 'advisor news', 'advisor news' ),
			'supports'            => array( 'title', 'revisions', 'thumbnail', 'author' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-admin-links',
			'can_export'          => true,
			'has_archive'         => 'advisor-news',
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'rewrite'             => array(
				'slug'       => 'advisor-news',
				'with_front' => false,
			),
			'capability_type'     => 'page',
		);
		register_post_type( static::$post_type, $args );

		$args = array(
			'labels'             => Kitces_Helpers::generate_taxonomy_labels( 'advisor news category', 'advisor news categories' ),
			'hierarchical'       => true,
			'public'             => true,
			'show_ui'            => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
		);
		register_taxonomy( 'advisor-news-category', array( static::$post_type ), $args );

		$args = array(
			'labels'             => Kitces_Helpers::generate_taxonomy_labels( 'publication', 'publications' ),
			'hierarchical'       => false,
			'public'             => true,
			'show_ui'            => true,
			'show_in_quick_edit' => true,
			'show_admin_column'  => true,
			'show_in_nav_menus'  => false,
			'show_tagcloud'      => false,
		);
		register_taxonomy( 'advisor-news-publication', array( static::$post_type ), $args );

		wp_register_script( 'kitces-collapsible', get_stylesheet_directory_uri() . '/lib/js/kitces-collapsible-commentary.js', array( 'jquery' ), '0.0.1', true );
	}

	/**
	 * Setup Advanced Custom Fields fields for post type
	 */
	public function action_acf_init() {
		$args = array(
			'key'         => 'advisor_news_fields',
			'title'       => 'Advisor News Fields',
			'fields'      => array(
				array(
					'key'   => 'field_advisor_news_url',
					'label' => 'URL',
					'name'  => 'advisor_news_url',
					'type'  => 'url',
				),
				array(
					'key'          => 'field_advisor_news_commentary',
					'label'        => 'Commentary',
					'name'         => 'advisor_news_commentary',
					'type'         => 'wysiwyg',
					'toolbar'      => 'basic',
					'media_upload' => false,
				),
			),
			'location'    => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => static::$post_type,
					),
				),
			),
			'description' => 'Advisor News fields',
		);
		acf_add_local_field_group( $args );
	}

	/**
	 * Redirect requests to single Advisor News items to the external URL if a value is entered in the field
	 */
	function action_template_redirect() {
		if ( is_singular( static::$post_type ) ) {
			$external_url = get_field( 'advisor_news_url' );
			if ( ! empty( $external_url ) ) {
				wp_safe_redirect( $external_url, 301 );
				die();
			}
		}
	}

	/**
	 * Update the post_content to match the value of the commentary field
	 *
	 * @param string  $value    The commentary value being saved
	 * @param integer $post_id The ID of the post being saved
	 *
	 * @return string The original commentary value
	 */
	public function filter_update_advisor_news_commentary_field( $value = '', $post_id = 0 ) {
		$post_args = array(
			'ID'           => $post_id,
			'post_content' => $value,
		);
		wp_update_post( $post_args );
		return $value;
	}

	/**
	 * When the News URL field is saved, get the domain and add it as a Publication tag
	 *
	 * @param string  $url      The URL field value being updated.
	 * @param integer $post_id The WordPress Post ID being updated.
	 */
	public function filter_update_advisor_news_url( $url = '', $post_id = 0 ) {
		$domain = static::get_url_domain( $post_id, $url );
		if ( ! empty( $domain ) ) {
			$args = array(
				'term_name' => $domain,
				'tax'       => 'advisor-news-publication',
				'obj_ids'   => $post_id,
			);
			Kitces_Helpers::maybe_add_term( $args );
		}
		return $url;
	}

	/**
	 * Get share data for a given post
	 *
	 * @param array $args Arguments to override default values.
	 */
	public static function get_share_data( $args = array() ) {
		$post         = get_post();
		$default_text = get_the_title( $post->ID );
		$default_url  = get_permalink( $post->ID );
		$default_args = array(
			'url'           => $default_url,

			'twitter_text'  => $default_text,
			'twitter_via'   => 'MichaelKitces',
			'twitter_url'   => '',
			'facebook_url'  => '',
			'linkedin_url'  => '',
			'reddit_url'    => '',

			'email_url'     => '',
			'email_text'    => $default_text,
			'email_subject' => $default_text,
			'email_body'    => '',
		);
		$args         = wp_parse_args( $args, $default_args );

		$urls_to_check = array(
			'twitter_url',
			'facebook_url',
			'linkedin_url',
			'reddit_url',
			'email_url',
		);
		foreach ( $urls_to_check as $key ) {
			if ( empty( $args[ $key ] ) && false !== $args[ $key ] ) {
				$args[ $key ] = $args['url'];
			}
		}

		if ( empty( $args['email_body'] ) ) {
			$args['email_body'] = $args['email_text'] . "\n\n" . $args['email_url'];
		}

		$args['twitter_share_url'] = add_query_arg(
			array(
				'url'  => rawurlencode( $args['twitter_url'] ),
				'text' => rawurlencode( $args['twitter_text'] ),
				'via'  => rawurlencode( $args['twitter_via'] ),
			),
			'https://twitter.com/share'
		);

		$args['facebook_share_url'] = add_query_arg(
			array(
				'u' => rawurlencode( $args['facebook_url'] ),
			),
			'https://www.facebook.com/sharer/sharer.php'
		);

		if ( $args['linkedin_url'] ) {
			$args['linkedin_share_url'] = add_query_arg(
				array(
					'url'  => rawurlencode( $args['linkedin_url'] ),
					'mini' => true,
				),
				'https://www.linkedin.com/shareArticle'
			);
		}

		if ( $args['reddit_url'] ) {
			$args['reddit_share_url'] = add_query_arg(
				array(
					'url' => rawurlencode( $args['reddit_url'] ),
				),
				'http://www.reddit.com/submit'
			);
		}

		if ( $args['email_url'] ) {
			$args['email_share_url'] = add_query_arg(
				array(
					'subject' => rawurlencode( $args['email_subject'] ),
					'body'    => rawurlencode( $args['email_body'] ),
				),
				'mailto:'
			);
		}

		return $args;
	}

	/**
	 * Parse a URL and return the domain
	 *
	 * @param string|integer|WP_Post $post A WordPress post to get the URL field for.
	 * @param string                 $url An optional URL to parse to bypass fetching a URL associated with a post.
	 */
	public static function get_url_domain( $post = null, $url = '' ) {
		if ( empty( $url ) ) {
			$post = get_post( $post );
			$url  = get_field( 'advisor_news_url', $post->ID );
		}
		$domain = '';
		if ( ! empty( $url ) ) {
			$url_parts = wp_parse_url( $url );
			if ( ! empty( $url_parts['host'] ) ) {
				$domain = $url_parts['host'];
				$domain = str_replace( 'www.', '', $domain );
			}
		}
		return $domain;
	}
}
Kitces_Advisor_News::get_instance();
