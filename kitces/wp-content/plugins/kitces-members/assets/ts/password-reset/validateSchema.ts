const validateSchema = (password: string, schema: any) => {
	if (schema.validate(password)) {
		return true;
	}

	return false;
};

export default validateSchema;
