// Import Packages
const TerserPlugin = require('terser-webpack-plugin');

module.exports = (options, coreConfig, settings) => {
	if (options.process.typescript) {
		settings.module.rules.push({
			test: /\.(tsx?)$/,
			exclude: /node_modules/,
			use: {
				loader: 'ts-loader'
			}
		});
	}

	if (coreConfig.minimize.js) {
		settings.optimization.minimizer.push(
			// https://github.com/webpack-contrib/terser-webpack-plugin
			new TerserPlugin()
		);
	}
};
