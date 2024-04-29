/* global __basedir */

// Import Modules.
const path = require('path');

const devMode = process.env.NODE_ENV !== 'production';
const sourcePath = `${__basedir}/lib`;
const destinationPath = `${__basedir}/src`;

module.exports = {
	mode: devMode ? 'development' : 'production',
	target: 'web',
	context: path.resolve(__dirname, '../lib'),
	entry: {
		index: ['./../lib/js/index.js'],
		style: ['./../lib/css/style.scss'],
		gutenstyle: ['./../lib/css/gutenberg-blocks/index.scss']
	},
	output: {
		path: path.join(__dirname, './../src'),
		filename: '[name].js',
		sourceMapFilename: '[file].map'
	},
	sourcePath,
	destinationPath,
	externals: {
		jquery: 'jQuery'
	},
	resolve: {},
	watch: !!devMode,
	watchOptions: {
		ignored: /node_modules/
	},
	process: {
		js: true,
		css: true,
		images: true,
		fonts: true
	},
	sourcemaps: {
		js: true,
		css: true
	},
	minimize: {
		js: !devMode,
		css: !devMode
	},
	images: {
		publicPath: 'src'
	},
	fonts: {
		publicPath: 'src'
	},
	plugins: {
		MiniCssExtractPlugin: true,
		FileManagerPlugin: true,
		ImageminPlugin: true,
		CleanWebpackPlugin: false,
		BundleAnalyzerPlugin: false
	},
	MiniCssExtractPlugin: {
		filename: '[name].css'
	},
	ImageminPlugin: {
		bail: false,
		cache: true,
		name: '[path][name].[ext]',
		imageminOptions: {
			plugins: [
				['mozjpeg', { progressive: true, quality: 75 }],
				['optipng', { optimizationLevel: 3 }],
				['gifsicle', { interlaced: false }],
				[
					// Something is off with the svgs and they are being broken.
					// 'svgo',
					// {
					// 	plugins: [
					// 		{ cleanupAttrs: true },
					// 		{ cleanupEnableBackground: true },
					// 		{ cleanupIDs: true },
					// 		{ cleanupNumericValues: { floatPrecision: 3 } },
					// 		{ collapseGroups: true },
					// 		{ convertColors: true },
					// 		{ convertPathData: true },
					// 		{ convertShapeToPath: true },
					// 		{ convertStyleToAttrs: true },
					// 		{ convertTransform: true },
					// 		{ inlineStyles: true },
					// 		{ mergePaths: true },
					// 		{ minifyStyles: false },
					// 		{ moveElemsAttrsToGroup: true },
					// 		{ moveGroupAttrsToElems: true },
					// 		{ removeComments: true },
					// 		{ removeAttrs: true },
					// 		{ removeDesc: true },
					// 		{ removeDoctype: true },
					// 		{ removeEditorsNSData: true },
					// 		{ removeElementsByAttr: true },
					// 		{ removeEmptyAttrs: true },
					// 		{ removeEmptyContainers: true },
					// 		{ removeEmptyText: true },
					// 		{ removeHiddenElems: true },
					// 		{ removeMetadata: true },
					// 		{ removeNonInheritableGroupAttrs: true },
					// 		{ removeTitle: true },
					// 		{ removeUnknownsAndDefaults: true },
					// 		{ removeUnusedNS: true },
					// 		{ removeUselessDefs: true },
					// 		{ removeUselessStrokeAndFill: true },
					// 		{ removeXMLProcInst: true },
					// 		{ transformsWithOnePath: true },
					// 		{ addAttributesToSVGElement: false },
					// 		{ addClassesToSVGElement: false },
					// 		{ cleanupListOfValues: false },
					// 		{ removeDimensions: true },
					// 		{ removeStyleElement: false },
					// 		{ removeViewBox: false },
					// 		{ removeXMLNS: true },
					// 		{ sortAttrs: false }
					// 	]
					// }
				]
			]
		}
	},
	FileManagerPluginDev: {
		silent: true,
		onEnd: {
			mkdir: [`${destinationPath}/styles`, `${destinationPath}/scripts`],
			move: [
				{ source: `${destinationPath}/style.css`, destination: `${__basedir}/style.css` },
				{ source: `${destinationPath}/style.css.map`, destination: `${__basedir}/style.css.map` },
				{ source: `${destinationPath}/index.js`, destination: `${destinationPath}/scripts/index.js` },
				{ source: `${destinationPath}/index.js.map`, destination: `${destinationPath}/scripts/index.js.map` }
			],
			delete: [`${destinationPath}/style.js`, `${destinationPath}/style.js.map`]
		}
	},
	FileManagerPluginProduction: {
		onEnd: {
			mkdir: [`${destinationPath}/styles`, `${destinationPath}/scripts`],
			move: [
				{ source: `${destinationPath}/style.css`, destination: `${__basedir}/style.css` },
				{ source: `${destinationPath}/index.js`, destination: `${destinationPath}/scripts/index.js` }
			],
			delete: [`${destinationPath}/style.js`, `${destinationPath}/style.js.map`]
		}
	},
	CleanWebpackPlugin: {}
};
