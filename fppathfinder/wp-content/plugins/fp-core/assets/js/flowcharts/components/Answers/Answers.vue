<template>
	<div class="answer-wrap">
		<div class="answer" v-on:click="clickAnswer" :data-destination="data.fp_flowcharts_node_answers_destination">
			<div class="answer-inner">
				<div class="answer__overlay"></div>
				<p v-html="data.fp_flowcharts_node_answers_text"></p>
				<input
					type="hidden"
					:id="`answer-${parentId}-${data.id}-hidden-text`"
					:name="'chart_data[questions][' + question + '][answer][text]'"
					:class="'answer-input-' + data.id"
					:value="data.fp_flowcharts_node_answers_text"
					disabled="disabled">
				<input
						type="hidden"
						:id="`answer-${parentId}-${data.id}-hidden-id`"
						:name="'chart_data[questions][' + question + '][answer][id]'"
						:value="data.id"
						disabled="disabled" />
				<input
						type="hidden"
						:id="`answer-${parentId}-${data.id}-hidden-parentId`"
						:name="'chart_data[questions][' + question + '][answer][parentId]'"
						:value="parentId"
						disabled="disabled" />
			</div>
		</div>
	</div>
</template>

<script>
// Import Packages
import animateScrollTo from 'animated-scroll-to';
// Import Modules
import showNodes from './../../modules/showNodes';
import getParent from './../../modules/getParent';
import getSiblings from './../../modules/getSiblings';
import toggleReviewBtn from './../../modules/toggleReviewBtn';
import clearQuestions from './../../modules/clearQuestions';
import clearSiblings from './../../modules/clearSiblings';
import clearDirectSiblings from './../../modules/clearDirectSiblings';
import clearClass from './../../modules/clearClass';
import addClass from './../../modules/addClass';
import enableInputs from './../../modules/enableInputs';

const selectedClass = 'selected';
const notSelectedClass = 'not-selected';

export default {
	name: 'Node',
	props: {
		data: Object,
		question: Number,
		parentId: String,
		savedData: Array
	},
	methods: {
		clickAnswer: function(event) {
			const container = document.getElementById('flowchart');
			const dest = event.currentTarget.getAttribute('data-destination');
			const target = document.body.querySelector(`[data-target="${dest}"]`);

			this.clearPrevious(event);
			this.setAttributes(event, target);
			animateScrollTo(
				target,
				{
					elementToScroll: container,
					speed: 50
				}
			);

			toggleReviewBtn();
		},
		clearPrevious: function (event) {
			clearClass(event.currentTarget.parentNode, notSelectedClass);
			clearQuestions();
			clearSiblings(event.currentTarget, selectedClass, notSelectedClass);
			clearDirectSiblings(event.currentTarget, selectedClass);
		},
		setAttributes: function(event, target) {
			addClass(target, 'show', 'last');
			addClass(target.querySelector('.node'), 'target');
			enableInputs(target);
			enableInputs(event.currentTarget);

			// Add selected class to click answer.
			event.currentTarget.parentNode.classList.add(selectedClass);

			// Add not-selected class to answers direct sibling answers
			const directSiblings = getSiblings(getParent(event.currentTarget, 2));
			directSiblings.forEach(directSibling => {
				addClass(directSibling.querySelector('.answer-wrap'), notSelectedClass);
			});
		},
	},
	mounted: function() {
		let vm = this;

		Vue.nextTick(function () {
			if (vm.savedData === undefined || Object.keys(vm.savedData).length === 0) {
				return false;
			}

			const data = JSON.parse(JSON.stringify(vm.savedData));

			if (Object.keys(data).length === 0) {
				return false;
			}

			Object.entries(data).forEach(question => {

				if (Object.keys(question).length === 0) {
					return;
				}

				const theQuestion = question[1];
				const node = document.getElementById(theQuestion.question.id);
				const answer = theQuestion.answer;

				setTimeout(() => {
					showNodes(data, node, `${theQuestion.question.id}-${answer.id}`, 'answer', answer.id);
				}, 100);
			});
		});
	}
}
</script>

<style lang="scss" scoped>
.answer-wrap {
	position: relative;
	width: 100%;
}

.answer {
	box-sizing: border-box;
	width: 100%;
	height: 100%;
	padding: 1rem;
	text-align: center;
	border: 1px solid #333333;
	box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);

	@media (max-width: 1246px) {
		height: auto;
	}

	p {
		margin: 0;
	}

	&:hover {
		cursor: pointer;
	}
}

.answer-inner {
}
</style>
