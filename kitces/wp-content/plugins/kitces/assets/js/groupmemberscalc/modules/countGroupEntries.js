// Import packages

// Import modules
import { isEmpty } from '../../modules/utils/utils';

const countGroupEntries = async count => {
	return new Promise(resolve => {
		try {
			const entries = document.querySelectorAll('.gpnf-nested-entries tr');

			if (!isEmpty(entries)) {
				for (let i = 0; i < entries.length; i++) {
					if (entries[i].hasAttribute('data-entryid')) {
						count++;
					}
				}
			}

			resolve(count);
		} catch (err) {
			console.error(err);
		}
	});
};

export default countGroupEntries;
