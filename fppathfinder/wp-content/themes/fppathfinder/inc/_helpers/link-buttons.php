<?php

function objectiv_link_button( $btn_details = null, $class = 'button', $download = false ) {
	global $post;
	if ( ! empty( $btn_details ) && ! empty( $btn_details['url'] ) ) {
		$url                = $btn_details['url'] ?? '';
		$target             = $btn_details['target'] ?? '';
		$title              = empty( $btn_details['title'] ) ? 'Learn More' : $btn_details['title'];
		$post_id            = ! empty( $post ) ? $post->ID : 0;
		$show_download_icon = 'download' === get_post_type( $post_id ) && is_single() && $download;
		$class              = $show_download_icon ? $class . ' pdf-preview-flow-chart-btn-wrapper' : $class;
		$icon_markup        = $show_download_icon ? '<span class="pdf-preview-flow-chart-btn"></span>' : '';
		$download_attr      = $download ? 'download' : '';

		return "<span class='${class}'>${icon_markup}<a target='${target}' href='${url}' ${download_attr}>${title}</a></span>";
	} else {
		return '';
	}
}

function objectiv_link_link( $link_details = null, $class = null ) {

	if ( ! empty( $link_details ) ) {
		$title  = $link_details['title'];
		$url    = $link_details['url'];
		$target = $link_details['target'];

		if ( empty( $title ) ) {
			$title = 'Learn More';
		}

		if ( ! empty( $url ) && ! empty( $title ) ) {
			return '<a class="' . $class . '" target="' . $target . '" href="' . $url . '">' . $title . '</a>';
		} else {
			return '';
		}
	} else {
		return '';
	}

}

function obj_do_download_buttons( $buttons = null ) {
	$disp_become_member_button = get_field( 'show_become_member_button' );
	$mem_page                  = get_field( 'become_member_page', 'option' );
	$mem_link                  = '';
	$mem_button                = false;

	if ( ! empty( $disp_become_member_button ) && $disp_become_member_button && ! empty( $mem_page ) ) {
		$mem_link   = get_permalink( $mem_page->ID );
		$mem_button = true;
	}

	if ( ! empty( $buttons ) ) {
		echo "<div class='download-buttons-grid'>";
		foreach ( $buttons as $ind => $button ) {
			$file = $button['download_file'];
			$text = $button['button_text'];
			if ( ! empty( $file ) && ! empty( $text ) ) {
				$da_button_deets = array(
					'title'  => $text,
					'url'    => $file['url'],
					'target' => '',
				);
				if ( $ind > 0 ) {
					echo objectiv_link_button( $da_button_deets, 'button', true );
				} else {
					echo objectiv_link_button( $da_button_deets, 'red-button', true );
				}
			}
		}
		if ( $mem_button ) { ?>
			<span class="button">
				<a href="<?php echo esc_url( $mem_link ); ?>">Become a Member</a>
			</span>
			<?php
		}
		echo '</div>';
	}
}

// PDF: Display Dynamic Button functionality
function obj_do_dynamic_download_buttons( $buttons ) {
	$disp_become_member_button = get_field( 'show_become_member_button' );
	$mem_page                  = get_field( 'become_member_page', 'option' );
	$mem_link                  = '';
	$mem_button                = false;

	if ( ! empty( $disp_become_member_button ) && $disp_become_member_button && ! empty( $mem_page ) ) {
		$mem_link   = get_permalink( $mem_page->ID );
		$mem_button = true;
	}

	if ( ! empty( $buttons ) ) {
		echo "<div class='download-buttons-grid'>";
		foreach ( $buttons as $ind => $button ) {
			$file             = $button['svg_file'];
			$text             = $button['button_text'];
			$additional_files = $button['additional_svg_files'];
			$files            = array();

			// If there are any additional svg files, merge with $file.
			if ( ! empty( $additional_files ) ) {

				// Clean up $additional_files.
				foreach ( $additional_files as $additional_file ) {
					$files[] = $additional_file['svg_file'];
				}

				// Add $file to the top of the $files array.
				array_unshift( $files, $file );
			} else {
				$files[] = $file;
			}

			$files_count = count( $files );

			// Get $files array ID and name items and convert each to a comma separated string.
			$files_ids = implode(
				',',
				array_map(
					function ( $entry ) {
						return $entry['ID'];
					},
					$files
				)
			);

			$files_names = implode(
				',',
				array_map(
					function ( $entry ) {
						return $entry['name'];
					},
					$files
				)
			);

			if ( ! empty( $file ) && ! empty( $text ) ) {
				if ( $ind > 0 ) {
					$i = $ind + 1;
					echo do_shortcode( "[pdf-generator-download svg='{$file['ID']}' filename='{$file['name']}' svgs='{$files_ids}' filenames='{$files_names}' svgs-count='{$files_count}' button_text='$text' number='{$i}']" );
				} else {
					echo do_shortcode( "[pdf-generator-download svg='{$file['ID']}' filename='{$file['name']}' svgs='{$files_ids}' filenames='{$files_names}' svgs-count='{$files_count}' button_text='$text' class='red-button']" );
				}
			}
		}
		$current_user = wp_get_current_user();
		$user_id      = $current_user->ID;
		if ( $mem_button && ! rcp_user_has_access( $user_id, 1 ) ) {
			?>
			<span class="button">
				<a href="<?php echo esc_url( $mem_link ); ?>">Become a Member</a>
			</span>
			<?php
		}
		echo '</div>';
	}
}

function obj_do_non_member_download_buttons( $buttons = null ) {
	if ( ! empty( $buttons ) ) {
		echo "<div class='download-buttons-grid'>";
		foreach ( $buttons as $ind => $button ) {
			if ( $ind > 0 ) {
				echo objectiv_link_button( $button, 'button' );
			} else {
				echo objectiv_link_button( $button, 'red-button' );
			}
		}
		echo '</div>';
	}
}
