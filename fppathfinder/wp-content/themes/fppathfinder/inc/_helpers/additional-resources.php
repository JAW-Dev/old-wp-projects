<?php

function objectiv_download_additional_resources( $resources = null ) {
	if ( ! empty( $resources ) ) { ?>
	<div class="additional-resources-list">
		<h4 class="additional-resources-title">Additional Resources</h4>
		<?php
		foreach ( $resources as $r ) :
			echo "<div class='additional-resource-link-wrap'>";
				echo objectiv_link_link( $r['link'] );
			echo '</div>';
		endforeach;
		?>
	</div>
	<?php
	}
}
