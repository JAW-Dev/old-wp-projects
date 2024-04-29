const statusBarHandler = (data, percentage) => {
	const percentageValueElement = document.getElementById('bundle-download-percentage');
	const statusBarElement = document.getElementById('bundle-download-status-bar');

	percentageValueElement.innerHTML = `${percentage}%`;
	statusBarElement.style.width = `${percentage}%`;
};

export default statusBarHandler;
