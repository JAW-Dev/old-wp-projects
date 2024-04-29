const getEntriesCost = count => {
	let multiplier;
	const { prices } = kitcesGroupPricing;

	switch (true) {
		case count === 1:
			multiplier = Number(prices[0]);
			break;
		case count >= 2 && count <= 4:
			multiplier = Number(prices[1]);
			break;
		case count >= 5 && count <= 15:
			multiplier = Number(prices[2]);
			break;
		case count >= 16 && count <= 50:
			multiplier = Number(prices[3]);
			break;
		default:
			multiplier = 0;
	}

	return count * multiplier;
};

export default getEntriesCost;
