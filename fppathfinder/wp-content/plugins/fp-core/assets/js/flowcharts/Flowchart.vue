<template>
  <div id="flowchart" class="flowchart" :style="{'border-color': colors.color3}">
    <div
      v-for="data in chartData.questions"
      v-bind:key="data.id"
      :id="data.fp_flowcharts_node_id"
      class="node-question"
      :class="questionClasses(data.id)"
      :data-answers="data.answerTotal"
      :data-target="data.fp_flowcharts_node_id">
      <Questions :data="data" :colors="colors" :id="data.id" :savedData="savedData.questions" :parentId="data.fp_flowcharts_node_id"/>
    </div>
    <div
      v-for="data in chartData.results"
      v-bind:key="data.id"
      :id="data.fp_flowcharts_node_id"
      class="node-result"
      :data-target="data.fp_flowcharts_node_id">
      <Results :data="data" :colors="colors" :savedData="savedData"/>
    </div>
  </div>
</template>

<script>
// Import Components
import Questions from './components/Questions/Questions.vue'
import Results from './components/Results/Results.vue'
// Import Modudels
import formatNodes from './modules/formatNodes';
import toggleReviewBtn from './modules/toggleReviewBtn';

const data = flowchartData.post.chart_data !== undefined ? flowchartData.post.chart_data : {};

export default {
  name: 'Flowchart',
  components: {
    Questions,
    Results
  },
  data() {
    return {
      rawData: flowchartData.chartData,
      chartData: formatNodes(flowchartData.chartData),
      colors: flowchartData.colors,
      savedData: Object.keys(data).length !== 0 ? data : {}
    }
  },
  methods: {
    questionClasses: function (id) {
      let classes = `count-${this.chartData.questions[id].answerTotal}`;

      // Show first question on load.
      if(id === 0) {
        classes += ' show first last';
      }

      return classes;
    }
  },
  mounted: function() {
    Vue.nextTick(function () {
      toggleReviewBtn();
    });
	}
}
</script>

<style lang="scss">
  @import "./Flowchart.scss";
</style>
