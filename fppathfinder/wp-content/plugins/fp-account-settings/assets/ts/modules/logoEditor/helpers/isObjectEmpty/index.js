const isObjectEmpty = obj => {
	if (!obj) {
		return false;
	}

	return !!(Object.entries(obj).length === 0 && obj.constructor === Object);
};

export default isObjectEmpty;
