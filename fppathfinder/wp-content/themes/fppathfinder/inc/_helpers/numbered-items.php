<?php

function objectiv_do_numbered_item_list( $items = null ) {
	if ( ! empty( $items ) ) {
		$items_count = count( $items );
		if ( $items_count % 2 == 0 ) {
			$midpoint = ( $items_count / 2 ) + 1;
		} else {
			$midpoint = ( ( $items_count + 1 ) / 2 ) + 1;
		}
		echo "<div class='items-grid-wrap'>";
		echo "<div class='items-grid__first lmb0'>";
		foreach ( $items as $key => $item ) {
			if ( ! empty( $item ) ) {
				$text   = $item['item_text'];
				$number = $key + 1;
			}
			if ( $number < $midpoint ) {
				objectiv_do_numbered_item( $text, $number );
			}
		}
		echo '</div>';
		echo "<div class='items-grid__second lmb0'>";
		foreach ( $items as $key => $item ) {
			if ( ! empty( $item ) ) {
				$text   = $item['item_text'];
				$number = $key + 1;
			}
			if ( $number >= $midpoint ) {
				objectiv_do_numbered_item( $text, $number );
			}
		}
		echo '</div>';
		echo '</div>';
	}
}

function objectiv_do_numbered_item( $text = null, $number = null ) {
	if ( ! empty( $text ) && ! empty( $number ) ) {
	?>
		<div class="items-grid__item">
			<div class="items-grid__item-number"><?php echo esc_html( $number ); ?></div>
			<div class="items-grid__item-text"><?php echo esc_html( $text ); ?></div>
		</div>
	<?php
	}
}
