global.__basedir = __dirname; // eslint-disable-line

const config = require('./config/webpack-config');
const coreConfig = require('./config/webpack/core-config');
const stats = require('./config/webpack/stats');
const devServer = require('./config/webpack/devserver');

const devMode = process.argv[process.argv.indexOf('--mode') + 1] !== 'production';

const settings = {
	mode: coreConfig.mode,
	target: coreConfig.target,
	context: coreConfig.context,
	devtool: devMode && (coreConfig.sourcemaps.js || coreConfig.sourcemaps.css) ? 'source-map' : false,
	entry: config.entry,
	output: coreConfig.output,
	externals: coreConfig.externals,
	watch: coreConfig.watch,
	watchOptions: coreConfig.watchOptions,
	optimization: { minimizer: [] },
	resolve: coreConfig.resolve,
	module: { rules: [] },
	plugins: [],
	stats,
	devServer
};

// Javascript
const javascript = require('./config/webpack/javascript');

javascript(config, coreConfig, settings);

// Javascript
const typescript = require('./config/webpack/typescript');

typescript(config, coreConfig, settings);

// CSS
const css = require('./config/webpack/css');

css(config, coreConfig, settings, devMode);

// Images
const images = require('./config/webpack/images');

images(config, coreConfig, settings);

// Fonts
const fonts = require('./config/webpack/fonts');

fonts(config, coreConfig, settings);

// Plugins
const plugins = require('./config/webpack/plugins');

plugins(config, coreConfig, settings);

module.exports = settings;
