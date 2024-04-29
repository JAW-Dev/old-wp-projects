const isEmpty = element => {
	if (element === undefined || element === null || element.length === 0) {
		return true;
	}

	return false;
};

export default isEmpty;
