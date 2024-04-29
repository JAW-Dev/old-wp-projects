<section class="icon-blurbs sectionpb sectionpt">
	<div class="wrap">
		<div class="icon-blurbs__inner">
			<?php
			obj_section_header( $sec_title );
			if ( ! empty( $icon_items ) ) {
				echo "<div class='icon-blurbs__blurb-wrap'>";
				foreach ( $icon_items as $ic_i ) :
					objectiv_icon_item( $ic_i );
				endforeach;
				echo '</div>';
			}
			?>
			<?php if ( ! empty( $sec_blurb ) ) : ?>
				<div class="icon-blurbs__section-blurb">
					<?php echo esc_html( $sec_blurb ); ?>
				</div>
			<?php endif;
			if ( ! empty( $button ) ) :
				echo "<div class='icon-blurbs__section-button-wrap'>";
				echo objectiv_link_button( $button );
				echo "</div>";
			endif;
			?>
		</div>
	</div>
</section>
