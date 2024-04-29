<?php
$date_divider = '';

function get_adivsor_news_item_url() {
	$url = get_field( 'advisor_news_url' );
	$url = add_query_arg(
		array(
			'utm_source' => 'Kitces.com',
			'utm_medium' => 'Kitces_Advisor_News',
		),
		$url
	);
	return $url;
}

add_filter(
	'the_content',
	function( $content = '' ) {
		if ( ! empty( $content ) ) {
			$post = get_post();
			if ( ! empty( $post->post_author ) ) {
				$author_name = get_the_author_meta( 'display_name', $post->post_author );
				$content    .= '<p class="advisor-news-author">Posted by ' . $author_name . '</p>';
			}
		}
		return $content;
	}
);

add_action(
	'genesis_after_header',
	function() {
		$args = array(
			'hero_title'     => 'Advisor News',
			'hero_sub_title' => 'Links and Commentary All About Financial News',
			'hero_bg_image'  => array(
				'sizes' => array(
					'large' => get_site_url() . '/wp-content/uploads/2020/02/strat-cons-smaller-1024x520.jpg',
					'large' => 'https://www.kitces.com/wp-content/uploads/2020/02/strat-cons-smaller-1024x520.jpg',
				),
			),
		);
		hero_head( $args );
	}
);

add_action(
	'genesis_before_entry',
	function() {
		global $date_divider;
		$post     = get_post();
		$the_date = get_the_date( '', $post );
		if ( $date_divider !== $the_date ) {
			$date_divider = $the_date;
			echo '<h1 class="advisor-news-date">' . $the_date . '</h1>';
		}

	}
);

add_filter(
	'genesis_post_date_shortcode',
	function( $output, $atts ) {
		$post         = get_post();
		$the_datetime = get_the_date( 'c', $post );
		$the_time     = get_the_date( get_option( 'time_format' ), $post );
		return '';
		return '<time class="entry-time" itemprop="datePublished" datetime="' . $the_datetime . '">' . $the_time . '</time>';
	},
	10,
	2
);

add_filter(
	'genesis_post_title_text',
	function( $title = '' ) {
		$external_url = get_adivsor_news_item_url();
		if ( ! empty( $external_url ) ) {
			$domain = Kitces_Advisor_News::get_url_domain();
			if ( ! empty( $domain ) ) {
				$title        .= ' <small class="domain">(' . $domain . ')</small>';
				$favicon_image = '<img src="https://favicon.yandex.net/favicon/' . $domain . '" class="favicon">';
				$title         = $favicon_image . ' ' . $title;
			}
		}

		return $title;
	}
);

add_filter(
	'genesis_attr_entry-content',
	function( $attr ) {
		$attr['class'] .= ' commentary--hidden';
		return $attr;
	}
);

add_action(
	'genesis_entry_header',
	function() {
		$args       = array(
			'url' => get_adivsor_news_item_url(),
		);
		$share_data = Kitces_Advisor_News::get_share_data( $args );
		$post       = get_post();
		$links      = array(
			array(
				'text' => 'Tweet',
				'url'  => $share_data['twitter_share_url'],
				'icon' => 'twitter',
			),
			array(
				'text' => 'Facebook',
				'url'  => $share_data['facebook_share_url'],
				'icon' => 'facebook',
			),
			array(
				'text' => 'LinkedIn',
				'url'  => $share_data['linkedin_share_url'],
				'icon' => 'linkedin',
			),
			array(
				'text' => 'Email',
				'url'  => $share_data['email_share_url'],
				'icon' => 'email',
			),
		);
		echo '<p class="advisor-news-social">';
		foreach ( $links as $link ) {
			echo '<a href="' . esc_url( $link['url'] ) . '" target="_blank" title="' . esc_attr( $link['text'] ) . '">' . Kitces_SVG::get_icon( $link['icon'] ) . '</a>';
		}

		if ( ! empty( $post->post_content ) ) {
			echo '<button class="commentary-trigger js-commentary-trigger">Show Commentary</button>';
		}
		echo '</p>';
	},
	12
);

add_filter(
	'genesis_attr_entry-title-link',
	function( $attr = array() ) {
		$external_url = get_adivsor_news_item_url();
        $attr['href'] = $external_url;
        $attr['data-title'] = get_the_title();
		return $attr;
	},
	11
);

wp_enqueue_script( 'kitces-collapsible' );
genesis();
