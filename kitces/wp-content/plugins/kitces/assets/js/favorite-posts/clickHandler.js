import ajaxHandlerSave from './ajaxHandlerSave';
import ajaxHandlerRemove from './ajaxHandlerRemove';

const clickHandler = () => {
	const bookmarks = document.querySelectorAll('.kitces-favorite-posts__select');
	let navWrap = jQuery('#inpost-nav-wrap');
	let postTitle = navWrap.data('post-title');

	if (!bookmarks) {
		return false;
	}

	bookmarks.forEach(bookmark => {
		bookmark.addEventListener('click', e => {
			e.preventDefault();

			if (!bookmark.classList.contains('selected')) {
				bookmark.classList.toggle('selected');
				bookmark.classList.toggle('unselected');

				if (bookmark.classList.contains('selected')) {
					ajaxHandlerSave();
					if (postTitle.length) {
						mk_ga_track_js_event('Article Save', 'save', postTitle);
					}
				}
			} else {
				bookmark.classList.toggle('selected');
				bookmark.classList.toggle('unselected');

				if (bookmark.classList.contains('unselected')) {
					ajaxHandlerRemove();
					if (postTitle.length) {
						mk_ga_track_js_event('Article Save', 'unsave', postTitle);
					}
				}
			}
		});
	});
};

export default clickHandler;
