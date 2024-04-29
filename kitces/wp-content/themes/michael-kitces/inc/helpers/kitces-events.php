<?php

function mk_ke_page_template_events( $template_page_id = null, $future = true, $order = 'DESC' ) {

	$return_events = false;

	if ( empty( $template_page_id ) ) {
		$template_page_id = get_the_ID();
	}

	$page_template_cats    = mk_get_field( 'category_sections', $template_page_id, true, true );
	$page_template_cat_ids = array();

	if ( ! empty( $page_template_cats ) ) {
		foreach ( $page_template_cats  as $cat ) {
			array_push( $page_template_cat_ids, $cat['category'] );
		}

		$args   = array(
			'posts_per_page' => -1,
			'post_type'      => 'kitces-event',
			'post_status'    => 'publish',
			'meta_key'       => 'date_time',
			'orderby'        => 'meta_value',
			'order'          => $order,
			'tax_query'      => array(
				array(
					'taxonomy' => 'ke-cat',
					'field'    => 'term_id',
					'terms'    => $page_template_cat_ids,
				),
			),
		);
		$events = get_posts( $args );

		$return_events = mk_ke_past_future_events( $events, $future );
	}

	return $return_events;
}

function mk_ke_past_future_events( $events = null, $future = true ) {
	if ( empty( $events ) ) {
		return null;
	}

	$returned_events   = array();
	$current_date_time = current_time( 'timestamp' );

	foreach ( $events as $event ) {
		$event_date_time   = get_post_meta( $event->ID, 'date_time', true );
		$event_date_number = strtotime( $event_date_time );

		if ( $future && $event_date_number >= $current_date_time ) {
			array_push( $returned_events, $event );
		} elseif ( ! $future && $event_date_number <= $current_date_time ) {
			array_push( $returned_events, $event );
		}
	}

	return $returned_events;
}

function mk_kitces_event_block( $event = null, $wide = true, $past = false, $member_details = false ) {

	if ( ! empty( $event ) && is_object( $event ) ) {

		$post_id               = $event->ID;
		$image_id              = get_post_thumbnail_id( $post_id );
		$image                 = ! empty( $image_id ) ? wp_get_attachment_image_url( $image_id, 'large' ) : null;
		$date_time             = get_post_meta( $post_id, 'date_time', true );
		$date_time             = strtotime( $date_time );
		$title                 = $event->post_title;
		$content               = $event->post_content;
		$buttons               = mk_get_field( 'buttons', $post_id, true, true );
		$replay_page           = mk_get_field( 'replay_page', $post_id );
		$width_class           = $wide ? 'is-wide' : 'is-not-wide';
		$register_form_embed   = mk_get_field( 'member_webinars_embed_code', $post_id );
		$display_register_form = false;
		$member_cta_text       = null;
		$speaker_headshot      = mk_get_field( 'speaker_headshot', $post_id, true, true );
		$speaker_name          = mk_get_field( 'speaker_name', $post_id );
		$speaker_blurb         = mk_get_field( 'speaker_blurb', $post_id );
		$display_speaker       = ! empty( $speaker_headshot ) && ! empty( $speaker_blurb ) && ! empty( $speaker_name );
		$is_reader_member      = kitces_is_valid_reader_member();

		if ( $wide && ! empty( $register_form_embed ) && ! $past && $member_details ) {
			$display_register_form = true;
			$width_class          .= ' has-register-form';
			$member_cta_text       = mk_get_field( 'member_cta_text', $post_id );
		}

		if ( $past ) {
			$formatted_date_time = date_i18n( 'F j, Y', $date_time );
			$has_access          = is_user_logged_in() && kitces_member_can_access_post( $post_id, get_current_user_id() );
			$member_page_id      = mk_get_field( 'global_become_a_member_page', 'option', true, true );
			$replay_link         = null;

			if ( $has_access && ! $is_reader_member ) {
				$replay_link = array(
					'title'  => 'View This Event',
					'url'    => get_permalink( $replay_page ),
					'target' => '',
				);
			} elseif ( ! empty( $member_page_id ) ) {
				$replay_link = array(
					'title'  => 'Become a Member to View This Event',
					'url'    => get_permalink( $member_page_id ),
					'target' => '',
				);
			}
		} else {
			$formatted_date_time = date_i18n( 'F j, Y @ g:i A', $date_time ) . ' EST';
		}

		?>
		<div class="kitces-event-block soft-shadow <?php echo $width_class; ?>">
			<?php if ( ! empty( $image ) || $display_register_form ) : ?>
				<?php if ( ! $display_register_form ) : ?>
					<div class="image-wrap bg-cover" style="background-image: url('<?php echo $image; ?>');"></div>
				<?php else : ?>
					<div class="image-wrap"><?php echo $register_form_embed; ?></div>
				<?php endif; ?>
			<?php endif; ?>
			<div class="content">
				<?php if ( ! empty( $formatted_date_time ) ) : ?>
					<div class="f18 fwb"><?php echo $formatted_date_time; ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $title ) ) : ?>
					<h4 class="f24 fwb mthalf ff-base"><?php echo $title; ?></h4>
				<?php endif; ?>
				<?php if ( ! empty( $content ) ) : ?>
					<div class="content-wrapper mt1 norm-list">
						<div class="last-child-margin-bottom-0">
							<?php echo wpautop( $content ); ?>
						</div>
						<?php if ( ! empty( $member_cta_text ) ) : ?>
							<div class="uppercase mt1 mb1 tc-text-med-blue fwb"><?php echo $member_cta_text; ?></div>
						<?php endif; ?>
						<?php if ( $display_speaker ) : ?>
							<div class="speaker-wrap mt1 mb1">
								<div class="headshot-wrap">
									<?php echo wp_get_attachment_image( $speaker_headshot['ID'], 'small-square' ); ?>
								</div>
								<div class="right-speaker-details">
									<h4><?php echo $speaker_name; ?></h4>
									<p><?php echo $speaker_blurb; ?></p>
								</div>
							</div>
						<?php endif; ?>
						<div class="content-wrapper-open">+Read More+</div>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $buttons ) && ! $past ) : ?>
					<div class="buttons-wrap">
						<?php foreach ( $buttons as $butt ) : ?>
							<?php
								$member_page_button = mk_key_value( $butt, 'member_section_button' );
							?>
							<?php if ( ! $member_page_button ) : ?>
								<?php echo mk_link_html( $butt['button'], 'button ' . $butt['button_color'] ); ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				<?php if ( ! empty( $replay_page ) && $past ) : ?>
					<div class="buttons-wrap">
						<?php echo mk_link_html( $replay_link, 'button' ); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
		<?php

	}
}

function mk_new_webinars_ke_event_block( $event = null, $button = null, $future = true ) {
	if ( ! empty( $event ) && ! empty( $button ) ) {
		$event_id            = $event->ID;
		$thumb_id            = get_post_thumbnail_id( $event_id );
		$thumb               = wp_get_attachment_image( $thumb_id, 'medium' );
		$title               = $event->post_title;
		$date_time           = get_post_meta( $event_id, 'date_time', true );
		$date_time           = strtotime( $date_time );
		$formatted_date_time = date_i18n( 'F j, Y h:i A', $date_time ) . ' EST';
		$speaker_name        = mk_get_field( 'speaker_name', $event_id );

		if ( ! $future ) {
			$formatted_date_time = 'Available Now';
		}
		?>
			<div class="member-ke-event">

				<?php if ( $future ) : ?>
					<div class="mbhalf f14 text-red fwb">Upcoming</div>
				<?php endif; ?>

				<?php if ( ! empty( $thumb ) ) : ?>
					<div class="thumb-wrap">
						<?php echo $thumb; ?>
					</div>
				<?php endif; ?>

				<div class="f16"><?php echo $formatted_date_time; ?></div>

				<div class="f16 fwb"><?php echo $speaker_name; ?></div>

				<a class="title-link" target="<?php echo $button['target']; ?>" href="<?php echo $button['url']; ?>">
					<div><?php echo $title; ?></div>
				</a>

				<div class="button-wrap">
					<?php echo mk_link_html( $button, 'button medium-button' ); ?>
				</div>
			</div>
		<?php
	}
}
