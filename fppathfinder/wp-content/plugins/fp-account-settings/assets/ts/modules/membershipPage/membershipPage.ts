import addClass from './addClass';

const membershipPage = (): void => {
	const statuses = document.querySelectorAll('.rcp_membership__status');

	addClass(statuses);
};

export default membershipPage;
