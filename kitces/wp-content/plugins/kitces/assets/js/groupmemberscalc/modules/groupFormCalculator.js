// Import modules
import createElements from './createElements';
import countGroupEntries from './countGroupEntries';
import updateGroupEntries from './updateGroupEntries';
import getEntriesCost from './getEntriesCost';
import { isEmpty } from '../../modules/utils/utils';

const groupFormCalculator = async () => {
	await createElements();

	const total = document.getElementById('member-total');
	const savings = document.getElementById('member-savings-total');

	if (!isEmpty(total)) {
		let count = 1;

		count = await countGroupEntries(count);
		total.innerHTML = `$${getEntriesCost(Number(count))}.00`;
		savings.innerHTML = `$${Number(count) * 149 - getEntriesCost(Number(count))}.00`;

		document.body.addEventListener('click', async e => {
			count = await updateGroupEntries(e, count);
			total.innerHTML = `$${getEntriesCost(Number(count))}.00`;
			savings.innerHTML = `$${Number(count) * 149 - getEntriesCost(Number(count))}.00`;
		});
	}
};

export default groupFormCalculator;
