<?php

function kitces_entry_header() {
	global $post;
	?>
	<div class="entry-header__wrap">
		<div class="entry-header__icon">
			<?php
			$post_terms = get_the_terms( $post->ID, 'category' );
			$post_term  = ! empty( $post_terms[0] ) ? $post_terms[0] : array();
			$tax_term   = ! empty( $post_term ) ? $post_term->taxonomy . '_' . $post_term->term_id : '';
			$icon_type  = ! empty( $tax_term ) && function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_type', $tax_term ) : '';
			$icon       = '';

			if ( ! empty( $icon_type ) ) {
				$icon = '';

				if ( 'font' === $icon_type ) {
					$icon = function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_font_icon', $tax_term ) : '';
				} elseif ( 'image' === $icon_type ) {
					$image_id = function_exists( 'get_field' ) ? get_field( 'kitces_category_icon_image_icon', $tax_term ) : '';
					$icon     = wp_get_attachment_image( $image_id, 'full', true );
				}
			}

			echo $icon; // phpcs:ignore
			?>
		</div>
		<div class="entry-header__body">
		<?php
		if ( ! is_home() && genesis_entry_header_hidden_on_current_page() ) {
			return;
		}

		$title = apply_filters( 'genesis_post_title_text', get_the_title() );

		if ( '' === trim( $title ) ) {
			return;
		}

		// Link it, if necessary.
		if ( ! is_singular() && apply_filters( 'genesis_link_post_title', true ) ) {
			$title = genesis_markup(
				[
					'open'    => '<a %s>',
					'close'   => '</a>',
					'content' => $title,
					'context' => 'entry-title-link',
					'echo'    => false,
				]
			);
		}

		// Wrap in H1 on singular pages.
		$wrap = is_singular() ? 'h1' : 'h2';

		// Also, if HTML5 with semantic headings, wrap in H1.
		$wrap = genesis_get_seo_option( 'semantic_headings' ) ? 'h1' : $wrap;

		// Wrap in H2 on static homepages if Primary Title H1 is set to title or description.
		if (
			is_front_page()
			&& ! is_home()
			&& genesis_seo_active()
			&& 'neither' !== genesis_get_seo_option( 'home_h1_on' )
		) {
			$wrap = 'h2';
		}

		/**
		 * Entry title wrapping element.
		 *
		 * The wrapping element for the entry title.
		 *
		 * @since 2.2.3
		 *
		 * @param string $wrap The wrapping element (h1, h2, p, etc.).
		 */
		$wrap = apply_filters( 'genesis_entry_title_wrap', $wrap );

		// Build the output.
		$output = genesis_markup(
			[
				'open'    => "<{$wrap} %s>",
				'close'   => "</{$wrap}>",
				'content' => $title,
				'context' => 'entry-title',
				'params'  => [
					'wrap' => $wrap,
				],
				'echo'    => false,
			]
		);

		echo apply_filters( 'genesis_post_title_output', $output, $wrap, $title ) . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- title output is left unescaped to accommodate trusted user input. See https://codex.wordpress.org/Function_Reference/the_title#Security_considerations.

		if ( ! post_type_supports( get_post_type(), 'genesis-entry-meta-before-content' ) ) {
			return;
		}

		$post_info = wp_kses_post( genesis_get_option( 'entry_meta_before_content' ) );
		$filtered  = apply_filters( 'genesis_post_info', $post_info );

		if ( '' === trim( $filtered ) ) {
			return;
		}

		genesis_markup(
			[
				'open'    => '<p %s>',
				'close'   => '</p>',
				'content' => genesis_strip_p_tags( $filtered ),
				'context' => 'entry-meta-before-content',
			]
		);

		?>
		</div>
	</div>
	<?php
}
