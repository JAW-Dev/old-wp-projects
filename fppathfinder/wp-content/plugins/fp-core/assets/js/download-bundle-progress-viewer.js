import pdfGeneratorInit from './pdf-bundles/index';

pdfGeneratorInit();

// jQuery(document).ready(initProgressViewer);

// function initProgressViewer() {
// 	const percentageValueElement = jQuery('#bundle-download-percentage');
// 	const statusBarElement = jQuery('#bundle-download-status-bar');

// 	const updateProgress = function (percent) {
// 		percentageValueElement.text(`${percent}%`);
// 		statusBarElement.width(`${percent}%`);
// 	};

// 	const generatorKickOff = function (bundleId, processId) {
// 		const ajaxSettings = {
// 			data: {
// 				action: 'generate_bundle',
// 				bundle_id: bundleId,
// 				process_id: processId
// 			},
// 			url: download_bundle_progress_viewer_object.ajax_url
// 		};

// 		jQuery.ajax(ajaxSettings);
// 	};

// 	const observer = function (jqXHR, textStatus) {
// 		const data = JSON.parse(jqXHR.responseText);

// 		if (data.file_url !== null) {
// 			updateProgress(100);
// 			const a = document.createElement('a');
// 			a.setAttribute('href', data.file_url);
// 			a.click();
// 			return;
// 		}

// 		updateProgress(data.percent);

// 		const observationRequestData = {
// 			action: 'generate_bundle_progress',
// 			id: data.process_id,
// 			nonce: data.nonce
// 		};

// 		const ajaxSettings = {
// 			data: observationRequestData,
// 			complete: observer,
// 			url: download_bundle_progress_viewer_object.ajax_url
// 		};

// 		jQuery.ajax(ajaxSettings);
// 	};

// 	const ajaxSettings = {
// 		data: {
// 			action: 'create_generate_bundle_process',
// 			id: download_bundle_progress_viewer_object.id,
// 			nonce: download_bundle_progress_viewer_object.nonce
// 		},
// 		url: download_bundle_progress_viewer_object.ajax_url,
// 		complete(jqXHR, textStatus) {
// 			const data = JSON.parse(jqXHR.responseText);

// 			generatorKickOff(data.bundle_id, data.process_id);
// 			observer(jqXHR, textStatus);
// 		}
// 	};

// 	jQuery.ajax(ajaxSettings);
// }
