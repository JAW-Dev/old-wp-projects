import statusBarHandler from './statusBarHandler';

const ajaxHandler = (bundle_dir = '', total_posts = 0, remaining_posts = {}, remaining_count = 0, pdfs = {}, first_run = 'true', temp_dir = '') => {
	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);
	const postId = urlParams.get('postid');
	const userId = download_bundle_progress_viewer_object.user_id;
	const userSettings = download_bundle_progress_viewer_object.user_settings;

	jQuery.ajax({
		type: 'post',
		url: download_bundle_progress_viewer_object.ajax_url,
		data: {
			action: 'generate_bundle_test',
			data: {
				post_id: postId,
				first_run,
				bundle_dir,
				total_posts,
				remaining_posts,
				remaining_count,
				pdfs,
				temp_dir,
				user_id: userId,
				user_settings: userSettings
			}
		},
		success(response) {
			if (response) {
				const data = JSON.parse(response);

				if (!data.complete) {
					const percentage = Math.round(((data.total_posts - data.remaining_count) / data.total_posts) * 100);

					statusBarHandler(data, percentage);

					if (data.remaining_count >= 0) {
						ajaxHandler(data.bundle_dir, data.total_posts, data.remaining_posts, data.remaining_count, data.pdfs, 'false', data.temp_dir);
					}
				}

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
			}
		},
		fail(err) {
			console.error(`There was an error: ${err}`);
		}
	});
};

export default ajaxHandler;
