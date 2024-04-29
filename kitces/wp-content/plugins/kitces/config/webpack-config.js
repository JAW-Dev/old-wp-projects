/* global __basedir */

// Import Modules.
const path = require('path');

module.exports = {
	entry: {
		'js/index': [path.resolve(__basedir, 'assets/js/index.js')],
		'css/index': [path.resolve(__basedir, 'assets/css/index.scss')],
		'js/emailLookup': [path.resolve(__basedir, 'assets/js/emailLookup.js')],
		'js/groupmemberscalc': [path.resolve(__basedir, 'assets/js/groupmemberscalc/index.js')],
		'css/groupmemberscalc': [path.resolve(__basedir, 'assets/css/groupmemberscalc.scss')],
		'js/acsync': [path.resolve(__basedir, 'assets/js/acsync/index.js')],
		'js/favorite-posts': [path.resolve(__basedir, 'assets/js/favorite-posts/index.js')],
		'css/favorite-posts': [path.resolve(__basedir, 'assets/css/favorite-posts.scss')],
		'js/favorite-posts-table': [path.resolve(__basedir, 'assets/js/favorite-posts-table/index.js')],
		'css/favorite-posts-table': [path.resolve(__basedir, 'assets/css/favorite-posts-table.scss')],
		'css/missed-articles': [path.resolve(__basedir, 'assets/css/missed-articles.scss')],
		'js/user': [path.resolve(__basedir, 'assets/js/user/index.js')]
	},
	process: {
		js: true,
		css: true,
		images: true,
		fonts: true,
		typescript: false,
		tailwind: false
	},
	plugins: {
		CleanWebpackPlugin: true,
		MiniCssExtractPlugin: true,
		FixStyleOnlyEntriesPlugin: true,
		ImageminPlugin: false,
		BundleAnalyzerPlugin: false
	}
};
