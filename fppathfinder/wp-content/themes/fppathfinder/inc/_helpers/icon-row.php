<?php

/**
 * Displays an icon row item.
 */
function objectiv_icon_item( $item = null ) {

	if ( ! empty( $item ) ) {
		$text = $item['text'];
		$icon = $item['icon'];

		if ( ! empty( $text ) && ! empty( $icon ) ) {
			echo "<div class='icon-text-item'>";
			echo "<div class='icon-text-item__icon'>";
				obj_svg( $icon );
			echo '</div>';
			echo "<div class='icon-text-item__text'>";
				echo esc_html( $text );
			echo '</div>';
			echo '</div>';
		}
	}

}

function obj_do_regular_image_icon_item( $item ) {
	if ( is_array( $item ) && ! empty( $item ) ) {
		echo "<div class='icon-text-item'>";
		echo "<div class='icon-text-item__icon'>";
		?>
			<img src="<?php echo $item['icon']['url']; ?>" alt="<?php echo $item['icon']['alt']; ?>">
		<?php
		echo '</div>';
		echo "<div class='icon-text-item__text'>";
		echo esc_html( $item['text'] );
		echo '</div>';
		echo '</div>';
	}
}
