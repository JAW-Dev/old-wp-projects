const ajaxHandlerSave = () => {
	const element = document.getElementById('kitces-favorite-posts-select');

	if (!element) {
		return false;
	}

	const { nonce, post, user } = element.dataset;

	jQuery.ajax({
		type: 'post',
		url: kitcesAdminAjax,
		data: {
			action: 'save_favorite_post',
			nonce,
			post,
			user
		}
	});
};

export default ajaxHandlerSave;
