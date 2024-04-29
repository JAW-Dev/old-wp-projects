<?php

function obj_accordion_row( $title = null, $icon_url = null, $content = null ) {
	?>
	<div class="accordion-row">
		<div class="accordion-row">
			<div class="accordion-title transition_3sec">
				<div class="title">
					<?php if ( ! empty( $icon_url ) ) : ?>
						<div class="accordion-icon-wrapper">
							<?php echo obj_svg( $icon_url ); ?>
						</div>
					<?php endif; ?>
					<?php echo $title; ?>
				</div>
				<div class="svg-wrap">
					<svg class="icon-plus" enable-background="new 0 0 100 100" id="Layer_1" version="1.1" viewBox="0 0 100 100" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
						<polygon class="plus_fill" fill="#a6a6a6" points="80.2,51.6 51.4,51.6 51.4,22.6 48.9,22.6 48.9,51.6 19.9,51.6 19.9,54.1 48.9,54.1 48.9,83.1   51.4,83.1 51.4,54.1 80.4,54.1 80.4,51.6 "/>
					</svg>
				</div>
			</div>
			<div class="accordion-info">
				<?php echo $content; ?>
			</div>
		</div>
	</div>
	<?php
}
