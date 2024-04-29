const alerts = () => {
	const saveNotificationArea = document.getElementById('save-notification-successful');
	saveNotificationArea.style.display = 'none';

	const formNotificationArea = document.getElementById('form-notification-area-wrap');
	formNotificationArea.style.display = 'none';
};

export default alerts;
