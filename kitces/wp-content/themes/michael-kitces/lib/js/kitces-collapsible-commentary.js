jQuery(document).ready(function($) {
	$('html')
		.removeClass('no-js')
		.addClass('js');

	$('.content').on('click', '.js-commentary-trigger', function(e) {
		const $btn = $(this);
		const $parent = $btn.parents('article');
		const expanded = $btn.attr('aria-expanded') === 'true' || false;
		$btn.attr('aria-expanded', !expanded);
		let btnText = 'Show Commentary';
		if (!expanded) {
			btnText = 'Hide Commentary';
		}
		$btn.text(btnText);
		$parent.find('.entry-content').toggleClass('commentary--hidden', expanded);
		e.preventDefault();
	});
});
