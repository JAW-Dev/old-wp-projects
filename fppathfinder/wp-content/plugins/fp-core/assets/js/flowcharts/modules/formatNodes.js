import lowercaseDash from './lowercaseDash';

const formatNodes = data => {
	const questions = [];
	const results = [];
	let intQCount = 0;
	let qCount = 0;

	data.forEach(item => {
		if (item.fp_flowcharts_node_type === 'question') {
			intQCount++;
		}
	});

	let rCount = intQCount;

	data.forEach(item => {
		if (item.fp_flowcharts_node_type === 'question') {
			let aCount = 0;
			item.id = qCount;
			item.fp_flowcharts_node_id = lowercaseDash(item.fp_flowcharts_node_id);
			questions.push(item);
			qCount++;

			if (item.fp_flowcharts_node_answers.length > 0) {
				item.fp_flowcharts_node_answers.forEach(answer => {
					answer.id = aCount;
					aCount++;
				});
			}
			item.answerTotal = aCount;
		}

		if (item.fp_flowcharts_node_type === 'results') {
			item.id = rCount;
			item.fp_flowcharts_node_id = lowercaseDash(item.fp_flowcharts_node_id);
			results.push(item);
			rCount++;
		}
	});

	const returnData = {
		questions,
		results
	};

	return returnData;
};

export default formatNodes;
