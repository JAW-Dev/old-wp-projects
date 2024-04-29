/* global __basedir */

// Require Modules.
const path = require('path');

const settings = {
	entry: {},
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
		RemoveEmptyScriptsPlugin: true,
		ImageminPlugin: false,
		BundleAnalyzerPlugin: false
	}
};

if (settings.process.js === true) {
	settings.entry['js/index'] = [path.resolve(__basedir, 'assets/js/index.js')];
}

if (settings.process.typescript === true) {
	settings.entry['js/index'] = [path.resolve(__basedir, 'assets/ts/index.ts')];
}

if (settings.process.css === true) {
	settings.entry['css/index'] = [path.resolve(__basedir, 'assets/css/index.scss')];
}

module.exports = settings;
