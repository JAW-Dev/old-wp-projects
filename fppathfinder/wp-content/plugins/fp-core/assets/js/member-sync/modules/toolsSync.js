import toolsSyncAjaxHandler from './toolsSyncAjaxHandler';

const toolsSync = () => {
	const button = document.getElementById('acsync-users-submit');

	if (!button) {
		return false;
	}

	button.addEventListener('click', e => {
		e.preventDefault();
		toolsSyncAjaxHandler(button);
	});
};

export default toolsSync;
