const validateHash = hash => {
	return /^#[0-9A-F]{6}$/i.test(`#${hash}`);
};

export default validateHash;
