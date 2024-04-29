import ajaxHandlerRemove from './ajaxHandlerRemove';

const tableDeleteClickHandler = () => {
	const deleteElements = document.querySelectorAll('.kitces-saved-articles__trash svg');

	deleteElements.forEach(deleteElement => {
		deleteElement.addEventListener('click', () => {
			const parent = deleteElement.parentElement;
			const { title } = parent.dataset;
			const confirmAction = confirm(`Are you sure you want to remove ${title} from your list?`); // eslint-disable-line

			if (confirmAction) {
				ajaxHandlerRemove(parent);
				mk_ga_track_js_event('Article Save', 'unsave', title);
			}
		});
	});
};

export default tableDeleteClickHandler;
