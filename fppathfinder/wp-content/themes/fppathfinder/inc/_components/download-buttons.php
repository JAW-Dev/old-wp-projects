<?php

/**
 * FP Get Link Button
 *
 * Return markup for a link button.
 *
 * @param string $href
 * @param string $target
 * @param string $text
 * @param string $class
 * @param bool   $is_download
 * @param bool   $download_icon
 */
function fp_get_download_buttons( string $href, string $target, string $text, string $class = 'button', bool $is_download = false, bool $download_icon = false ) {
	$download_attribute = $is_download ? 'download' : '';
	$icon               = $download_icon ? '<span class="pdf-preview-flow-chart-btn"></span>' : '';

	return "<span class='${class}'>${icon}<a target='${target}' href='${href}' ${download_attribute}>${text}</a></span>";
}
