import ajaxHandler from './ajaxHandler';

const clickHandler = () => {
	const button = document.getElementById('merge-member-button');

	button?.addEventListener('click', e => {
		e.preventDefault();

		const nonce: string = button.dataset.nonce!;
		const userId = button.dataset.userid!;
		const memberEmail = button.dataset.mergeEmail!;
		const userEmail = button.dataset.useremail!;

		const confirmMerge = confirm(
			`User ${memberEmail} will be merged into ${userEmail}. Only ${userEmail} will remain. This action cannot be undone. Are you sure you want to continue?`
		);

		if (confirmMerge) {
			const message = document.getElementById('member-merge-message');
			message!.innerHTML = '';
			message!.style.display = 'none';

			ajaxHandler(button, nonce, userId, memberEmail);
		}
	});
};

export default clickHandler;
