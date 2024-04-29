<template>
	<div class="wrap">
		<div class="node">
			<div class="node__body" :style="{'background-color': colors.color3}">
				<p v-html="data.fp_flowcharts_node_text" :class="contrast"></p>
				<input
					type="hidden"
					:id="'result-' + data.fp_flowcharts_node_id + '-hidden-text'"
					name="chart_data[result][text]"
					:value="data.fp_flowcharts_node_text"
					disabled="disabled">
				<input
					type="hidden"
					:id="'result-' + data.fp_flowcharts_node_id + '-hidden-id'"
					name="chart_data[result][id]"
					:value="data.fp_flowcharts_node_id"
					disabled="disabled">
			</div>
		</div>
	</div>
</template>

<script>
// Import modules
import showNodes from './../../modules/showNodes'
import colorContrast from './../../modules/colorContrast';

export default {
	name: 'Results',
	props: {
		data: Object,
		colors: Object,
		savedData: Object
	},
	mounted: function() {
    let vm = this;

    Vue.nextTick(function () {
			if (vm.savedData === undefined || Object.keys(vm.savedData).length === 0 ) {
				return false;
			}

			const data = JSON.parse(JSON.stringify(vm.savedData));

			if (Object.keys(data).length === 0) {
				return false;
			}

			const resultId = data.result.id;
			const node = document.getElementById(resultId);
			showNodes(data, node, resultId, 'result');
    });
	},
		computed: {
		contrast : function() {
			return colorContrast(this.colors.color1) === 'light' ? 'light-background' : 'dark-backround';
		}
	}
}
</script>

<style lang="scss" scoped>
.wrap {
	display: flex;
	flex-direction: column;
	align-items: center;
	width: 100%;
}

.node {
	position: relative;
	box-sizing: content-box;
	display: flex;
	justify-content: center;
	width: 300px;
	padding: 2rem 0 2rem;

	&__body {
		width: 100%;
		padding: 1rem;
		color: white;
		text-align: center;
		box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);

		p {
			margin: 0;
		}
	}
}
</style>
