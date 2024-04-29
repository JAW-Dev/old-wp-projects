module.exports = (options, coreConfig, settings) => {
	if (options.process.images) {
		settings.module.rules.push({
			test: /\.(gif|png|jpe?g|webp|svg)$/i,
			use: [
				{
					loader: 'file-loader',
					options: {
						name: '[path][name].[ext]',
						useRelativePath: true,
						outputPath: coreConfig.images.outputPath,
						publicPath: coreConfig.images.publicPath
					}
				}
			]
		});
	}
};
