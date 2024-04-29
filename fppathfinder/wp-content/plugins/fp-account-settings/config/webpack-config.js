/* global __basedir */

// Require Modules.
const path = require('path');

module.exports = {
	entry: {
		'js/index': [path.resolve(__basedir, 'assets/ts/index.ts')],
		'css/index': [path.resolve(__basedir, 'assets/css/index.scss')],
		'js/global': [path.resolve(__basedir, 'assets/ts/global.ts')],
		'css/global': [path.resolve(__basedir, 'assets/css/global.scss')]
	},
	process: {
		js: false,
		css: true,
		images: true,
		fonts: true,
		typescript: true,
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
