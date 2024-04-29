const ajaxHandlerRemove = element => {
	if (!element) {
		return false;
	}

	const { nonce, post, user } = element.dataset;

	jQuery.ajax({
		type: 'post',
		url: kitcesAdminAjax,
		data: {
			action: 'remove_favorite_post',
			nonce,
			post,
			user
		},
		success() {
			location.reload();
		}
	});
};

export default ajaxHandlerRemove;
