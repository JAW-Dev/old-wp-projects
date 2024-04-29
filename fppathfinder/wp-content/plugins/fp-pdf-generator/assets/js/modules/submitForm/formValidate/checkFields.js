const checkFields = requiredFields => {
	const entries = Object.entries(requiredFields);

	for (const key of entries) {
		if (key[1].value.length === 0) {
			return false;
		}
	}

	return true;
};

export default checkFields;
