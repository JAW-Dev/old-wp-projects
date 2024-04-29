const deleteAjaxHandler = data => {
	if (data.complete) {
		if (data.zip !== null) {
			statusBarHandler(data, 100);
			const a = document.createElement('a');
			a.setAttribute('href', data.zip_file);
			a.click();

			jQuery.ajax({
				type: 'post',
				url: download_bundle_progress_viewer_object.ajax_url,
				data: {
					action: 'generate_bundle_delete',
					bundle_dir
				},
				fail(err) {
					console.error(`There was an error: ${err}`);
				}
			});
		}
	}
};

export default deleteAjaxHandler;
