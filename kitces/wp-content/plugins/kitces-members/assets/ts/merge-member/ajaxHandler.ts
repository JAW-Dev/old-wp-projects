declare var ajaxurl: any;

const ajaxHandler = (button: HTMLElement, nonce: string, userId: String, memberEmail: string) => {
	jQuery.ajax({
		type: 'post',
		url: ajaxurl,
		data: {
			action: 'merge_member',
			nonce,
			user_id: userId,
			member_email: memberEmail
		},
		beforeSend() {
			button.innerHTML = 'Merging Member';
		},
		success(response) {
			const message = document.getElementById('member-merge-message');
			const inputField = document.getElementById('merge-member-email') as HTMLInputElement;

			switch (response) {
				case 'no-member-email':
					message?.classList.remove('notice-success');
					message?.classList.add('notice-error');
					message!.innerHTML = `<p>You must supply a member email to merge!</p>`;
					message!.style.display = 'block';
					button.innerHTML = 'Error!';
					break;
				case 'not-a-user':
					message!.innerHTML = `<p>Unable to find user ${memberEmail}!</p>`;
					message!.style.display = 'block';
					button.innerHTML = 'Error!';
					break;
				case 'no-user-id-error':
					message?.classList.remove('notice-success');
					message?.classList.add('notice-error');
					message!.innerHTML = `<p>Unable to find user's ID for ${memberEmail}!</p>`;
					message!.style.display = 'block';
					button.innerHTML = 'Error!';
					break;
				case 'merge-failed-error':
					message?.classList.remove('notice-success');
					message?.classList.add('notice-error');
					message!.innerHTML = `<p>Merge with ${memberEmail} failed!</p>`;
					message!.style.display = 'block';
					button.innerHTML = 'Error!';
					break;
				case 'success':
					message?.classList.remove('notice-error');
					message?.classList.add('notice-success');
					message!.innerHTML = `<p>Member ${memberEmail} Merged!</p>`;
					message!.style.display = 'block';
					button.innerHTML = 'Member Merged';
					inputField!.value = '';
					break;
			}

			setTimeout(() => {
				button.innerHTML = 'Merge Member';
				message?.classList.remove('notice-success');
				message?.classList.remove('notice-error');
				message!.style.display = 'none';
			}, 2000);
		}
	});
};

export default ajaxHandler;
