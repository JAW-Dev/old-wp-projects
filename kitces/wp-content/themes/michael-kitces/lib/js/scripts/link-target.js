jQuery(document).ready(function () {
	var all_links = jQuery('a');
	for (var i = 0; i < all_links.length; i++) {
		var a = all_links[i];
		if (a.hostname != location.hostname) {
			a.rel = 'noopener';
			a.target = '_blank';
		}
	}
});