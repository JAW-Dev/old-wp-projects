/* global __basedir */

// Import Modules.
const path = require('path');

module.exports = {
	entry: {
		'js/integrations': [path.resolve(__basedir, 'assets/js/integrations/index.js')],
		'js/resource-links': [path.resolve(__basedir, 'assets/js/resource-links/index.js')],
		'js/contact-lookup': [path.resolve(__basedir, 'assets/js/contact-lookup/index.js')],
		'js/flowcharts': [path.resolve(__basedir, 'assets/js/flowcharts/index.js')],
		'js/active-campaign-tracking-pixel': [path.resolve(__basedir, 'assets/js/active-campaign-tracking-pixel.js')],
		'js/download-bundle-progress-viewer': [path.resolve(__basedir, 'assets/js/download-bundle-progress-viewer.js')],
		'css/integrations': [path.resolve(__basedir, 'assets/css/integrations.scss')],
		'css/resource-links': [path.resolve(__basedir, 'assets/css/resource-links.scss')],
		'css/index': [path.resolve(__basedir, 'assets/css/index.scss')],
		'js/members-sync': [path.resolve(__basedir, 'assets/js/member-sync/index.js')],
		'js/comped-membership': [path.resolve(__basedir, 'assets/js/comped-membership/index.js')],
		'css/global': [path.resolve(__basedir, 'assets/css/global.scss')]
	},
	process: {
		js: true,
		css: true,
		images: true,
		fonts: true,
		typescript: false,
		tailwind: true,
		vue: true
	},
	plugins: {
		CleanWebpackPlugin: false,
		MiniCssExtractPlugin: true,
		FixStyleOnlyEntriesPlugin: true,
		ImageminPlugin: false,
		BundleAnalyzerPlugin: false,
		VueLoaderPlugin: true
	}
};
