module.exports = (options, coreConfig, settings) => {
	if (options.process.vue) {
		settings.module.rules.push({
			test: /.vue$/,
			loader: 'vue-loader'
		});
	}
};
