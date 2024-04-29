/* global __basedir */

// Import Modules.
const path = require('path');

module.exports = {
	entry: {
		'js/index': [path.resolve(__basedir, 'assets/js/index.js')],
		'css/index': [path.resolve(__basedir, 'assets/css/index.scss')]
	},
	externals: {
		jquery: 'jQuery'
	},
	process: {
		js: true,
		css: true,
		images: false,
		fonts: false,
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
