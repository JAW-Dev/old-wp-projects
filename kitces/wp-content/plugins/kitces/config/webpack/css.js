// Import Packages
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const autoPrefixer = require('autoprefixer');
const cssnano = require('cssnano');
const tailwind = require('tailwindcss');
const globImporter = require('node-sass-glob-importer');
const jsonImporter = require('node-sass-json-importer');
const sass = require('node-sass');
const sassUtils = require('node-sass-utils')(sass);
const purgecss = require('@fullhuman/postcss-purgecss')({
	content: ['./**/*.html', './**/*.php'],
	defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
});

module.exports = (options, coreConfig, settings, devMode) => {
	const cssPurge = process.env.NODE_ENV === 'production' ? [purgecss] : [];
	const postcssPlugins = [autoPrefixer({ grid: true }), ...cssPurge];

	if (options.tailwind) {
		postcssPlugins.push(tailwind);
	}

	if (options.process.css) {
		settings.module.rules.push({
			test: /\.(sa|sc|le|c)ss$/,
			use: [
				{
					loader: MiniCssExtractPlugin.loader
				},
				{
					loader: 'css-loader',
					options: { sourceMap: !!(devMode && coreConfig.sourcemaps.css) }
				},
				{
					loader: 'postcss-loader',
					options: {
						ident: 'postcss',
						plugins: postcssPlugins,
						sourceMap: !!(devMode && coreConfig.sourcemaps.css)
					}
				},
				{
					loader: 'sass-loader',
					options: {
						sourceMap: !!(devMode && coreConfig.sourcemaps.css),
						sassOptions: {
							importer: [globImporter(), jsonImporter()],
							functions: {
								'config($keys)': function (keys) {
									keys = keys.getValue().split('.');
									let result = options;
									let i;
									for (i = 0; i < keys.length; i++) {
										result = result[keys[i]];
									}
									result = sassUtils.castToSass(result);
									return result;
								}
							}
						}
					}
				}
			]
		});
	}

	if (coreConfig.minimize.css) {
		postcssPlugins.push(cssnano);
	}
};
