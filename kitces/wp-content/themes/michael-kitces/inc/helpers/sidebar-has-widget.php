<?php

function sidebar_has_widget( $ids ) {
	global $wp_registered_sidebars;
	$widgetcolums = wp_get_sidebars_widgets();

	foreach ( $wp_registered_sidebars as $sidebar ) {
		$sidebar_id = isset( $sidebar['id'] ) ? $sidebar['id'] : '';
		if ( ! empty( $sidebar_id ) ) {
			$widgets = ! empty( $widgetcolums[ $sidebar_id ] ) ? $widgetcolums[ $sidebar_id ] : '';

			if ( ! empty( $widgets ) ) {
				foreach ( $widgets as $widget ) {
					if ( is_array( $ids ) ) {
						foreach ( $ids as $id ) {
							if ( strpos( $widget, $id ) !== false ) {
								return true;
							}
						}
					} else {
						if ( strpos( $widget, $ids ) !== false ) {
							return true;
						}
					}
				}
			}
		}
	}

	return false;
}