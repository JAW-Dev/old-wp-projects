const slideNotification = (element: string, duration: number = 2000) => {
	const notification = <HTMLFormElement>document.getElementById(element);
	notification.classList.add('show');

	setTimeout(() => {
		notification.classList.remove('show');
	}, duration);
};

export default slideNotification;
