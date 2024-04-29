<?php

function objectiv_author_block( $author_id = null, $post_id = null ) {

	// If we dont have an author, return now.
	if ( empty( $author_id ) ) {
		return null;
	}

	// Set post id if not passed
	if ( empty( $post_id ) ) {
		$post_id = get_the_ID();
	}

	$additional_authors = get_field( 'additional_authors', $post_id );
	$single_author      = true;

	// Decide if single author or multi
	if ( ! empty( $additional_authors ) && is_array( $additional_authors ) ) {
		$single_author = false;
	}

	return objectiv_get_author_section_markup( $author_id, $single_author, $additional_authors );
}

function objectiv_get_author_section_markup( $author_id = null, $single_author = true, $additional_authors = null ) {

	// If its a single author just output the block
	// If its a multiple, output the difference in the header and then loop through

	if ( $single_author ) {
		$author_box = objectiv_get_one_individual_author_markup( $author_id, $single_author );
	} else {
		$author_box  = "<div class='multiple-author-wrap'>";
		$author_box .= "<h2 class='multiple-author-title'>Authors:</h2>";
		$author_box .= objectiv_get_one_individual_author_markup( $author_id, $single_author );
		foreach ( $additional_authors as $author ) {
			$author_box .= objectiv_get_one_individual_author_markup( $author, $single_author );
		}
		$author_box .= '</div>';
	}

	return $author_box;
}

function objectiv_get_one_individual_author_markup( $author_id, $single_author ) {
	$ac_id                = "user_{$author_id}";
	$photo                = get_field( 'author_photo', $ac_id );
	$bio                  = get_field( 'author_bio', $ac_id );
	$facebook             = get_field( 'author_facebook_url', $ac_id );
	$twitter              = get_field( 'author_twitter_url', $ac_id );
	$linkedin             = get_field( 'author_linkedin_url', $ac_id );
	$website              = get_field( 'author_website_url', $ac_id );
	$email                = get_field( 'author_email_address', $ac_id );
	$user_display_name    = is_object( get_userdata( $author_id ) ) ? get_userdata( $author_id )->display_name : '';
	$name_override        = get_field( 'author_name', $ac_id );
	$display_author_block = ! empty( $photo ) && ! empty( $bio ) && ! empty( $user_display_name );
	$display_social       = ! empty( $facebook ) || ! empty( $twitter ) || ! empty( $linkedin ) || ! empty( $website ) || ! empty( $email );
	$k_team               = false;
	$on_team              = get_field( 'author_kitces_team_member', $ac_id );

	if ( ! empty( $name_override ) ) {
		$user_display_name = $name_override;
	}

	if ( $on_team ) {
		$k_team = true;
	}

	$single_author_block = null;

	if ( $display_author_block ) {
		ob_start();

		echo "<div class='author-block'>";
		echo "<div class='author-block__first tac'>";
		echo wp_get_attachment_image( $photo['id'], 'author-photo' );
		if ( $display_social ) {
			echo "<div class='author-block__social-links'>";
			if ( ! empty( $facebook ) ) {
				echo "<a target='_blank' title='Facebook' href='$facebook' class='social-icon'><svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Facebook_w_x2F__circle' x='0px' y='0px' viewBox='0 0 20 20' enable-background='new 0 0 20 20' xml:space='preserve'><path d='M10,0.4c-5.302,0-9.6,4.298-9.6,9.6s4.298,9.6,9.6,9.6s9.6-4.298,9.6-9.6S15.302,0.4,10,0.4z M12.274,7.034h-1.443  c-0.171,0-0.361,0.225-0.361,0.524V8.6h1.805l-0.273,1.486H10.47v4.461H8.767v-4.461H7.222V8.6h1.545V7.726  c0-1.254,0.87-2.273,2.064-2.273h1.443V7.034z'></path></svg></a>";
			}
			if ( ! empty( $twitter ) ) {
				echo "<a target='_blank' title='Twitter' href='$twitter' class='social-icon'><svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Twitter_w_x2F__circle' x='0px' y='0px' viewBox='0 0 20 20' enable-background='new 0 0 20 20' xml:space='preserve'><path d='M10,0.4c-5.302,0-9.6,4.298-9.6,9.6s4.298,9.6,9.6,9.6s9.6-4.298,9.6-9.6S15.302,0.4,10,0.4z M13.905,8.264  c0.004,0.082,0.005,0.164,0.005,0.244c0,2.5-1.901,5.381-5.379,5.381c-1.068,0-2.062-0.312-2.898-0.85  c0.147,0.018,0.298,0.025,0.451,0.025c0.886,0,1.701-0.301,2.348-0.809c-0.827-0.016-1.525-0.562-1.766-1.312  c0.115,0.021,0.233,0.033,0.355,0.033c0.172,0,0.34-0.023,0.498-0.066c-0.865-0.174-1.517-0.938-1.517-1.854V9.033  C6.257,9.174,6.549,9.26,6.859,9.27C6.351,8.93,6.018,8.352,6.018,7.695c0-0.346,0.093-0.672,0.256-0.951  c0.933,1.144,2.325,1.896,3.897,1.977c-0.033-0.139-0.049-0.283-0.049-0.432c0-1.043,0.846-1.891,1.891-1.891  c0.543,0,1.035,0.23,1.38,0.598c0.431-0.086,0.835-0.242,1.2-0.459c-0.141,0.441-0.44,0.812-0.831,1.047  c0.383-0.047,0.747-0.148,1.086-0.299C14.595,7.664,14.274,7.998,13.905,8.264z'></path></svg></a>";
			}
			if ( ! empty( $linkedin ) ) {
				echo "<a target='_blank' title='LinkedIn' href='$linkedin' class='social-icon'><svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='LinkedIn_w_x2F__circle' x='0px' y='0px' viewBox='0 0 20 20' enable-background='new 0 0 20 20' xml:space='preserve'><path d='M10,0.4c-5.302,0-9.6,4.298-9.6,9.6s4.298,9.6,9.6,9.6s9.6-4.298,9.6-9.6S15.302,0.4,10,0.4z M7.65,13.979H5.706V7.723H7.65  V13.979z M6.666,6.955c-0.614,0-1.011-0.435-1.011-0.973c0-0.549,0.409-0.971,1.036-0.971c0.627,0,1.011,0.422,1.023,0.971  C7.714,6.52,7.318,6.955,6.666,6.955z M14.75,13.979h-1.944v-3.467c0-0.807-0.282-1.355-0.985-1.355  c-0.537,0-0.856,0.371-0.997,0.728c-0.052,0.127-0.065,0.307-0.065,0.486v3.607H8.814v-4.26c0-0.781-0.025-1.434-0.051-1.996h1.689  l0.089,0.869h0.039c0.256-0.408,0.883-1.01,1.932-1.01c1.279,0,2.238,0.857,2.238,2.699V13.979z'></path></svg></a>";
			}
			if ( ! empty( $email ) ) {
				echo "<a target='_blank' title='Email' href='mailto:$email' class='social-icon'><svg xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink' version='1.1' id='Mail_w_x2F__circle' x='0px' y='0px' viewBox='0 0 20 20' enable-background='new 0 0 20 20' xml:space='preserve'><path d='M10,0.3999634c-5.3019409,0-9.5999756,4.2980957-9.5999756,9.6000366S4.6980591,19.5999756,10,19.5999756  S19.5999756,15.3019409,19.5999756,10S15.3019409,0.3999634,10,0.3999634z M6.2313232,7h7.5195923  c0.3988037,0,0.1935425,0.5117188-0.0234985,0.6430664c-0.217041,0.1308594-3.2213135,1.9470215-3.333313,2.0144043  s-0.256958,0.0996094-0.402771,0.0996094c-0.145874,0-0.2908325-0.0322266-0.402771-0.0996094  C9.4765625,9.5900879,6.472229,7.7739258,6.255188,7.6430664C6.038208,7.5117188,5.8328857,7,6.2313232,7z M14,12.5  c0,0.2099609-0.251709,0.5-0.444458,0.5H6.444458C6.251709,13,6,12.7099609,6,12.5c0,0,0-3.5544434,0-3.6467285  c0-0.0917969-0.001709-0.2114258,0.171875-0.109375c0.246521,0.1445312,3.265625,1.9250488,3.416687,2.013916  c0.151001,0.0888672,0.256897,0.0995483,0.402771,0.0995483c0.145813,0,0.251709-0.0106812,0.402771-0.0995483  s3.1875-1.8688965,3.434021-2.0134277C14.001709,8.642334,14,8.7619629,14,8.8537598C14,8.9460449,14,12.5,14,12.5z'></path></svg></a>";
			}
			if ( ! empty( $website ) ) {
				echo "<a target='_blank' title='Website' href='$website' class='social-icon'><svg width='300px' height='300px' viewBox='0 0 300 300' version='1.1' xmlns='http://www.w3.org/2000/svg' xmlns:xlink='http://www.w3.org/1999/xlink'><g id='Page-1' stroke='none' stroke-width='1' fill='none' fill-rule='evenodd'><g id='Group' fill='#333333'><path d='M202.105,96.266 C196.531,90.861 190.125,86.309 183.094,82.829 C186.489,87.988 189.477,93.958 191.961,100.616 C195.528,99.337 198.918,97.882 202.105,96.266 Z' id='Path'></path><path d='M112.603,144.813 L144.81,144.813 L144.81,117.523 C135.185,117.105 125.879,115.749 117.209,113.534 C114.613,123.016 112.987,133.634 112.603,144.813 Z' id='Path'></path><path d='M116.579,183.953 C125.441,181.629 134.973,180.195 144.81,179.759 L144.81,155.187 L112.605,155.187 C112.964,165.39 114.359,175.121 116.579,183.953 Z' id='Path'></path><path d='M120.412,103.666 C128.06,105.57 136.282,106.745 144.81,107.136 L144.81,75.3 C143.308,75.404 141.822,75.552 140.346,75.744 C132.374,80.578 125.392,90.555 120.412,103.666 Z' id='Path'></path><path d='M96.922,202.79 C103.608,209.51 111.558,214.964 120.378,218.779 C116.106,212.684 112.439,205.323 109.537,196.98 C105.009,198.65 100.782,200.593 96.922,202.79 Z' id='Path'></path><path d='M120.288,81.26 C112.269,84.741 104.981,89.585 98.702,95.499 C102.315,97.436 106.223,99.138 110.358,100.624 C113.098,93.276 116.452,86.761 120.288,81.26 Z' id='Path'></path><path d='M107.195,110.479 C101.557,108.477 96.289,106.083 91.488,103.321 C82.257,114.868 76.375,129.187 75.299,144.813 L102.213,144.813 C102.594,132.454 104.343,120.861 107.195,110.479 Z' id='Path'></path><path d='M102.216,155.187 L75.3,155.187 C76.317,169.978 81.628,183.61 90.021,194.814 C95.016,191.813 100.572,189.204 106.563,187.041 C104.094,177.305 102.574,166.573 102.216,155.187 Z' id='Path'></path><path d='M140.536,224.283 C141.949,224.459 143.373,224.602 144.81,224.701 L144.81,190.147 C135.979,190.562 127.451,191.828 119.548,193.866 C124.604,208.249 132.008,219.207 140.536,224.283 Z' id='Path'></path><path d='M195.766,187 C201.101,188.932 206.104,191.212 210.679,193.837 C218.659,182.819 223.712,169.558 224.7,155.19 L200.105,155.19 C199.748,166.557 198.233,177.277 195.766,187 Z' id='Path'></path><path d='M183.011,217.213 C190.831,213.356 197.875,208.174 203.869,201.963 C200.43,200.114 196.713,198.456 192.774,197.009 C190.115,204.636 186.821,211.445 183.011,217.213 Z' id='Path'></path><path d='M149.997,0 C67.158,0 0.003,67.161 0.003,149.997 C0.003,232.833 67.158,300 149.997,300 C232.836,300 299.997,232.837 299.997,149.997 C299.997,67.157 232.837,0 149.997,0 Z M150,240.462 C100.12,240.462 59.538,199.883 59.538,150 C59.538,100.117 100.12,59.538 150,59.538 C199.88,59.538 240.462,100.117 240.462,150 C240.462,199.883 199.88,240.462 150,240.462 Z' id='Shape' fill-rule='nonzero'></path><path d='M162.719,76.202 C160.245,75.777 157.732,75.476 155.185,75.299 L155.185,107.236 C164.519,106.961 173.537,105.724 181.896,103.639 C177.074,90.952 170.375,81.195 162.719,76.202 Z' id='Path'></path><path d='M195.121,110.471 C197.977,120.853 199.725,132.452 200.106,144.813 L224.698,144.813 C223.653,129.586 218.04,115.604 209.214,104.218 C204.854,106.596 200.139,108.692 195.121,110.471 Z' id='Path'></path><path d='M155.185,224.7 C157.675,224.531 160.134,224.236 162.553,223.829 C170.754,218.567 177.86,207.827 182.765,193.881 C174.152,191.658 164.81,190.338 155.185,190.048 L155.185,224.7 Z' id='Path'></path><path d='M185.102,113.508 C175.718,115.91 165.609,117.321 155.185,117.611 L155.185,144.813 L189.719,144.813 C189.332,133.627 187.703,122.998 185.102,113.508 Z' id='Path'></path><path d='M189.716,155.187 L155.185,155.187 L155.185,179.673 C165.917,179.961 176.237,181.395 185.758,183.88 C187.97,175.07 189.358,165.364 189.716,155.187 Z' id='Path'></path></g></g></svg></a>";
			}
			echo '</div>';
		}
		echo '</div>';
		echo "<div class='author-block__second'>";
		echo "<div class='author-block__author-title'>";
		if ( $single_author ) {
			echo "<h3 class='author-block__name'>Author: $user_display_name</h3>";
		} else {
			echo "<h3 class='author-block__name'>$user_display_name</h3>";
		}
		if ( $k_team ) {
			echo "<div class='author-block__badge mk_team'>";
			echo 'Team Kitces';
			echo '</div>';
		} else {
			echo "<div class='author-block__badge guest_team'>";
			echo 'Guest Contributor';
			echo '</div>';
		}
		echo '</div>';
		echo "<div class='author-block__blurb last-child-margin-bottom-0 first-child-margin-top-0 f16'>";
		echo "$bio";
		if ( ! $single_author ) {
			echo "<div class='author-block__blurb__more'>+ Read More +</div>";
		}
		echo '</div>';
		if ( ! $single_author ) {
			echo "<div class='author-block__blurb__divider'></div>";
		}
		echo '</div>';
		echo '</div>';

		$single_author_block = ob_get_contents();
		ob_end_clean();

		return $single_author_block;

	} else {
		return null;
	}
}
