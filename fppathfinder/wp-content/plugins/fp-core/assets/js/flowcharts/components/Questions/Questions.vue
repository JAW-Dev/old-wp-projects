<template>
	<div class="qa-wrap">
		<div class="node">
			<div class="node__body" :style="{'background-color': colors.color1}">
				<p v-html="data.fp_flowcharts_node_text" :class="contrast"></p>
				<input
					type="hidden"
					:id="`question-${data.fp_flowcharts_node_id}-hidden-text`"
					:name="'chart_data[questions][' + data.id + '][question][text]'"
					:value="data.fp_flowcharts_node_text"
					:disabled="isDisabled" />
				<input
					type="hidden"
					:id="`question-${data.fp_flowcharts_node_id}-hidden-id`"
					:name="'chart_data[questions][' + data.id + '][question][id]'"
					:value="data.fp_flowcharts_node_id"
					:disabled="isDisabled" />
			</div>
		</div>
		<div class="answers">
			<div class="answers__wrap">
				<div v-for="item in data.fp_flowcharts_node_answers" v-bind:key="item.id" :id="`${parentId}-${item.id}`" :class="`answers__item answer-${item.id}`">
					<Answers :data="item" :question="data.id" :parentId="parentId" :savedData="savedData"/>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
// Import Components
import Answers from './../Answers/Answers.vue';
// Import Modules
import showNodes from './../../modules/showNodes';
import colorContrast from './../../modules/colorContrast';

export default {
	name: 'Questions',
	components: {
		Answers
	},
	props: {
		data: Object,
		id: Number,
		colors: Object,
		parentId: String,
		savedData: Array
	},
	data() {
		return {
			isDisabled: false
		}
	},
	mounted: function() {
		let vm = this;

		Vue.nextTick(function () {
			vm.isDisabled = vm.id === 0 ? false : true;

			if (vm.savedData === undefined || Object.keys(vm.savedData).length === 0 ) {
				return false;
			}

			const data = JSON.parse(JSON.stringify(vm.savedData));

			if (Object.keys(data).length === 0) {
				return false;
			}
			Object.entries(data).forEach(question => {
				const questionId = Object.keys(question[1]).length !== 0 ? question[1].question.id : '';
				const node = document.getElementById(questionId);

				setTimeout(() => {
					showNodes(data, node, questionId, 'question');
				}, 100);

			});
		});
	},
	methods: {
	},
	computed: {
		contrast : function() {
			return colorContrast(this.colors.color1) === 'light' ? 'light-background' : 'dark-backround';
		}
	}
}
</script>

<style lang="scss" scoped>
.qa-wrap {
	display: flex;
	flex-direction: column;
	align-items: center;
	width: 100%;
	padding: 0;
	margin: 0;
}

.light-background {
	color: #3f3f3f;
}

.dark-backround {
	color: #ffffff;
}

@import "./Node.scss";
@import "./Answers.scss";

</style>
