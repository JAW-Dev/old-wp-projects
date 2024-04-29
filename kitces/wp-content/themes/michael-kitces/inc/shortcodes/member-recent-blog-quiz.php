<?php

function mk_member_recent_blog_quiz_shortcode( $atts = array() ) {

	$title                 = mk_key_value( $atts, 'title' );
	$parent_page_id        = mk_key_value( $atts, 'parent_page_id' );
	$take_quiz_button_text = mk_key_value( $atts, 'take_quiz_button_text' );
	$recent_post           = null;
	$recent_post_details   = array();

	if ( $parent_page_id ) {
		$recent_post = get_posts( 'numberposts=1&orderby=date&order=DESC&post_type=page&post_status=private,publish&post_parent=' . $parent_page_id );

		if ( ! empty( $recent_post ) ) {
			$recent_post_details = mk_subpage_row_row( true, $recent_post[0], null, false, false, false );
		}
	}

	$recent_id                 = mk_key_value( $recent_post_details, 'post_id' );
	$recent_title              = mk_key_value( $recent_post_details, 'title' );
	$recent_link               = mk_key_value( $recent_post_details, 'link' );
	$recent_cfp_hours          = mk_key_value( $recent_post_details, 'cfp_hours' );
	$recent_nasba_hours        = mk_key_value( $recent_post_details, 'nasba_hours' );
	$recent_ea_hours           = mk_key_value( $recent_post_details, 'ea_hours' );
	$recent_iar_epr          = mk_key_value( $recent_post_details, 'iar_epr' );
	$recent_iar_pp          = mk_key_value( $recent_post_details, 'iar_pp' );
	$recent_availability       = mk_key_value( $recent_post_details, 'availability' );
	$recent_availability_class = mk_key_value( $recent_post_details, 'availability_class' );
	$recent_id                 = mk_key_value( $recent_post_details, 'post_id' );
	$recent_cfp_hours          = ! empty( $recent_cfp_hours ) ? $recent_cfp_hours : '0';
	$recent_nasba_hours        = ! empty( $recent_nasba_hours ) ? $recent_nasba_hours : '0';
	$recent_ea_hours           = ! empty( $recent_ea_hours ) ? $recent_ea_hours : '0';
	$recent_iar_epr			   = ! empty( $recent_iar_epr ) ? $recent_iar_epr : '0';
	$recent_iar_pp			   = ! empty( $recent_iar_pp ) ? $recent_iar_pp : '0';

	ob_start();
	?>
	<?php if ( ! empty( $recent_post_details ) && kitces_member_can_take_quiz( $recent_id ) ) : ?>
		<div class="member-recent-blog-quiz">
			<?php if ( ! empty( $title ) ) : ?>
				<div class="title"><?php echo wp_kses_post( $title ); ?></div>
			<?php endif; ?>
			<div class="most-recent-wrap">
				<a href="<?php echo $recent_link; ?>" class="quiz-title"><span class="inner-title"><?php echo $recent_title; ?></span></a>
				<div class="table-wrap">
					<table class="kitces-quiz-list">
						<thead>
							<tr>
								<td style="text-align: center;">CFP® &amp; IWI</td>
								<td style="text-align: center;">CPA</td>
								<td style="text-align: center;">EA</td>
								<td style="text-align: center;">IAR E&PR</td>
								<td style="text-align: center;">IAR P&P</td>
								<?php if ( $recent_availability ) : ?>
									<td style="text-align: center;">Status</td>
								<?php endif; ?>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="text-align: center;"><?php echo $recent_cfp_hours; ?></td>
								<td style="text-align: center;"><?php echo $recent_nasba_hours; ?></td>
								<td style="text-align: center;"><?php echo $recent_ea_hours; ?></td>
								<td style="text-align: center;"><?php echo $recent_iar_epr; ?></td>
								<td style="text-align: center;"><?php echo $recent_iar_pp; ?></td>
								<?php if ( $recent_availability ) : ?>
									<td style="text-align: center;"><span class="<?php echo $recent_availability_class; ?>"><?php echo $recent_availability; ?></span></td>
								<?php endif; ?>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="button-wrap">
					<?php if ( ! empty( $take_quiz_button_text ) ) : ?>
						<a href="<?php echo $recent_link; ?>" class="button medium-button"><?php echo $take_quiz_button_text; ?></a>
					<?php else : ?>
						<a href="<?php echo $recent_link; ?>" class="button medium-button">Take Quiz</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
	<?php
	return ob_get_clean();
}
add_shortcode( 'member-recent-blog-quiz', 'mk_member_recent_blog_quiz_shortcode' );
