const updateGroupEntries = async (e, count) => {
	return new Promise(resolve => {
		try {
			setTimeout(() => {
				if (e.target.className === 'gpnf-add-entry') {
					const modal = document.querySelector('.tingle-modal-box');
					const addButton = modal.querySelector('.gpnf-btn-submit');

					addButton.addEventListener('click', () => {
						count++;
						resolve(count);
					});
				}

				if (e.target.parentNode.className === 'delete') {
					count--;
					resolve(count);
				}
			});
		} catch (err) {
			console.error(err);
		}
	});
};

export default updateGroupEntries;
