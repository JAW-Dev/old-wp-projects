<?php

function mk_member_new_webinars_shortcode( $atts = false ) {
	$title                    = mk_key_value( $atts, 'title' );
	$ke_page_id               = mk_key_value( $atts, 'ke_page_id' );
	$pe_button_text           = mk_key_value( $atts, 'pe_button_text' );
	$ke_past_events           = null;
	$ke_upcoming_events       = null;
	$ke_upcoming_event        = null;
	$ke_upcoming_event_button = null;
	$ke_past_event            = null;
	$ke_past_event_button     = null;
	$is_reader_member         = kitces_is_valid_reader_member();
	$is_basic_member          = kitces_is_valid_basic_member() || $is_reader_member;
	$is_premier_member        = kitces_is_valid_premier_member();

	if ( ! empty( $ke_page_id ) ) {
		$ke_past_events     = mk_ke_page_template_events( $ke_page_id, false );
		$ke_upcoming_events = mk_ke_page_template_events( $ke_page_id, true, 'ASC' );

		if ( ! empty( $ke_past_events ) && is_array( $ke_past_events ) && ! $is_reader_member ) {
			foreach ( $ke_past_events as $pe ) {
				if ( empty( $ke_past_event ) ) {
					$replay_page = mk_get_field( 'replay_page', $pe->ID );
					if ( $replay_page ) {
						$ke_past_event        = $pe;
						$ke_past_event_button = array(
							'title'  => $pe_button_text,
							'url'    => get_permalink( $replay_page ),
							'target' => '',
						);
					}
				}
			}
		}

		if ( ! empty( $ke_upcoming_events ) && is_array( $ke_upcoming_events ) ) {
			foreach ( $ke_upcoming_events as $ue ) {
				if ( empty( $ke_upcoming_event ) ) {
					$buttons = mk_get_field( 'buttons', $ue->ID, true, true );

					if ( ! empty( $buttons ) && is_array( $buttons ) ) {
						foreach ( $buttons as $button ) {
							$memb_button  = mk_key_value( $button, 'member_section_button' );
							$member_level = mk_key_value( $button, 'member_level' );

							if ( $memb_button ) {
								if ( 'basic' === $member_level && $is_basic_member ) {
									$ke_upcoming_event        = $ue;
									$ke_upcoming_event_button = $button['button'];
								} elseif ( 'premier' === $member_level && $is_premier_member ) {
									$ke_upcoming_event        = $ue;
									$ke_upcoming_event_button = $button['button'];
								}
							}
						}
					}
				}
			}
		}
	}

	$return_html = ! empty( $title ) && ( ! empty( $ke_past_event ) || ! empty( $ke_upcoming_event ) );

	ob_start(); ?>
	<?php if ( $return_html ) : ?>
		<div class="member-new-webinars">
			<?php if ( ! empty( $title ) ) : ?>
				<div class="title"><?php echo wp_kses_post( $title ); ?></div>
			<?php endif; ?>
			<div class="member-new-webinars-wrap">
				<?php if ( ! empty( $ke_upcoming_event ) ) : ?>
					<?php mk_new_webinars_ke_event_block( $ke_upcoming_event, $ke_upcoming_event_button ); ?>
				<?php endif; ?>
				<?php if ( ! empty( $ke_past_event ) ) : ?>
					<?php mk_new_webinars_ke_event_block( $ke_past_event, $ke_past_event_button, false ); ?>
				<?php endif; ?>
			</div>
		</div>
	<?php endif; ?>
	<?php
	return ob_get_clean();
}
add_shortcode( 'member-new-webinars', 'mk_member_new_webinars_shortcode' );
