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
function fp_get_link_button( string $href, string $target, string $text, string $class = 'button', bool $is_download = false, bool $download_icon = false, $inner_class = null ) {
	$download_attribute = $is_download ? 'download' : '';
	$icon               = $download_icon ? fp_get_download_icon() : '';
	$style_when_icon    = 'style="display: flex; align-items: center; justify-content: center;"';
	$style              = $download_icon ? $style_when_icon : '';

	return "<span class='{$class}'><a class='{$inner_class}' target='{$target}' href='{$href}' {$download_attribute} {$style}>{$icon}<div>{$text}</div></a></span>";
}

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
function fp_get_link_button_no_follow( string $href, string $target, string $text, string $class = 'button', bool $is_download = false, bool $download_icon = false, $inner_class = null ) {
	$download_attribute = $is_download ? 'download' : '';
	$icon               = $download_icon ? fp_get_download_icon() : '';
	$style_when_icon    = 'style="display: flex; align-items: center; justify-content: center;"';
	$style              = $download_icon ? $style_when_icon : '';

	return "<span class='{$class}'><a rel='nofollow' class='{$inner_class}' target='{$target}' href='{$href}' {$download_attribute} {$style}>{$icon}<div>{$text}</div></a></span>";
}

/**
 * FP Get Download Icon
 *
 * Return markup for download icon.
 *
 * @return string
 */
function fp_get_download_icon() {
	return "<img src='/wp-content/themes/fppathfinder/assets/icons/src/download-icon.png' class='download-button-icon'>";
}
