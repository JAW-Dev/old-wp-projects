/* global __basedir */

// Require Modules.
const path = require('path');

const settings = {
	entry: {},
	process: {
		js: false,
		css: true,
		images: true,
		fonts: false,
		typescript: true,
		tailwind: false
	},
	plugins: {
		CleanWebpackPlugin: false,
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
	settings.entry['js/credits-form'] = [path.resolve(__basedir, 'assets/ts/credits-form/index.ts')];
	settings.entry['js/password-reset'] = [path.resolve(__basedir, 'assets/ts/password-reset/index.ts')];
	settings.entry['js/merge-member'] = [path.resolve(__basedir, 'assets/ts/merge-member/index.ts')];
}

if (settings.process.css === true) {
	settings.entry['css/password-reset'] = [path.resolve(__basedir, 'assets/css/password-reset.scss')];
	settings.entry['css/credits-form'] = [path.resolve(__basedir, 'assets/css/credits-form.scss')];
}

module.exports = settings;
