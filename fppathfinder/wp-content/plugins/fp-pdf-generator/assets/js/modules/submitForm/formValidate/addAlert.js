const addAlert = (requiredFields, formNotificationAreaList) => {
	Object.keys(requiredFields).forEach(key => {
		if (requiredFields[key].value.length === 0) {
			const splitKey = key
				.split(/(?=[A-Z])/)
				.map(str => str.charAt(0).toUpperCase() + str.slice(1))
				.join(' ');
			const li = document.createElement('li');
			li.innerHTML = `<span><label>${splitKey}</label> field is empty</span>`;
			formNotificationAreaList[0].appendChild(li);
		}
	});
};

export default addAlert;
