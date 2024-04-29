function addAlertElement() {
	const form = document.getElementById('memb_password_change-1');
	if ( form ) {
		addMarkup(form);
	}
}

function addAlertElement2() {
	const form = document.getElementById('resetpasswordform');
	if ( form ) {
		addMarkup(form);
	}
}

function addMarkup(form) {
	if ( form ) {
		form.innerHTML = '<div id="form-alert" style="display: none; margin-bottom: 1rem; border: solid 1px red; padding: 0.5rem; background-color: white;"><ul style="margin: 0; padding:0"></ul></div>' + form.innerHTML;
	}
}

function checkConfirmPassword() {
	const firstPassword = document.getElementById('memb_password_change-1-password1');
	const secondPaswword = document.getElementById('memb_password_change-1-password2');
	if ( firstPassword && secondPaswword ) {
		checkConfirmPasswordHandler(firstPassword, secondPaswword);
	}
}

function checkConfirmPassword2() {
	const firstPassword = document.getElementById('som_new_user_pass');
	const secondPaswword = document.getElementById('som_new_user_pass_again');
	if ( firstPassword && secondPaswword ) {
		checkConfirmPasswordHandler(firstPassword, secondPaswword);
	}
}

function checkConfirmPasswordHandler(firstPassword, secondPaswword) {
	let timeout = null;
	if ( firstPassword && secondPaswword ) {
		secondPaswword.addEventListener("keyup", event => {
			clearAlert();
			clearTimeout(timeout);
			if ( !passwordsMatch(firstPassword, secondPaswword) ) {
				secondPaswword.style.backgroundColor = 'rgba(86, 163, 217, 0.25)';
				timeout = setTimeout(function () {
					showAlert('Your passwords don\'t match!');
				}, 1000);
			} else {
				secondPaswword.style.backgroundColor = 'white';
			}
		});
	}
}

function checkFirstPasswordLength() {
	const firstPassword = document.getElementById('memb_password_change-1-password1');
	if (firstPassword) {
		passwordCheckHandler(firstPassword)
	}
}

function checkFirstPasswordLength2() {
	const firstPassword = document.getElementById('som_new_user_pass');
	if (firstPassword) {
		passwordCheckHandler(firstPassword)
	}
}

function checkSecondPasswordLength() {
	const secondPaswword = document.getElementById('memb_password_change-1-password2');
	if (secondPaswword) {
		passwordCheckHandler(secondPaswword);
	}
}

function checkSecondPasswordLength2() {
	const secondPaswword = document.getElementById('som_new_user_pass_again');
	if (secondPaswword) {
		passwordCheckHandler(secondPaswword);
	}
}

function passwordCheckHandler(element) {
	let timeout = null;
	if ( element ) {
		element.addEventListener("keyup", event => {
			clearAlert();
			clearTimeout(timeout);
			if ( !passwordMinLength(element, 8) ) {
				element.style.backgroundColor = 'rgba(86, 163, 217, 0.25)';
				timeout = setTimeout(function () {
					showAlert('The password must be at least 8 characters!');
				}, 1000);
			} else {
				element.style.backgroundColor = 'white';
			}
		});
	}
}

function checkSubmit() {
	const form = document.getElementById('memb_password_change-1');
	const firstPassword = document.getElementById('memb_password_change-1-password1');
	const secondPaswword = document.getElementById('memb_password_change-1-password2');

	if (firstPassword && secondPaswword){
		checkSubmitHandler(form, firstPassword, secondPaswword)
	}
}

function checkSubmit2() {
	const form = document.getElementById('resetpasswordform');
	const firstPassword = document.getElementById('som_new_user_pass');
	const secondPaswword = document.getElementById('som_new_user_pass_again');

	if (firstPassword && secondPaswword){
		checkSubmitHandler(form, firstPassword, secondPaswword)
	}
}

function checkSubmitHandler(form, firstPassword, secondPaswword) {
	if (firstPassword && secondPaswword){
		form.addEventListener('submit', event => {
			clearAlert();
			if ( !passwordMinLength(firstPassword, 8) || !passwordMinLength(secondPaswword, 8)) {
				event.preventDefault();
				showAlert('The password must be at least 8 characters!');
			}

			if ( !passwordsMatch(firstPassword, secondPaswword) ) {
				console.log('Doesn\'t Match' ); // eslint-disable-line
				event.preventDefault();
				showAlert('Your passwords don\'t match!');
			}
		});
	}
}

function showAlert( message ) {
	const element = document.getElementById('form-alert');
	if (element) {
		element.style.display = 'block';
		element.innerHTML = '';
		element.innerHTML = '<li>' + message + '</li>' + element.innerHTML;
	}
}

function clearAlert() {
	const element = document.getElementById('form-alert');
	if (element) {
		element.style.display = 'none';
		element.innerHTML = '';
	}
}

function enableButton(element) {
	element.removeAttribute('disabled');
}

function disableButton(element) {
	element.setAttribute('disabled', 'disabled');
}

function passwordsMatch(firstPassword, secondPaswword) {
	if ( firstPassword && secondPaswword) {
		if (secondPaswword.value === firstPassword.value) {
			return true;
		}
	}
	return false;
}

function passwordMinLength(element, length) {
	if (element) {
		return element.value.length >= length;
	}
}

document.addEventListener("DOMContentLoaded", function() {
	addAlertElement();
	checkFirstPasswordLength();
	checkSecondPasswordLength()
	checkConfirmPassword();
	checkSubmit();
});

// Frontend Password Plugin
addAlertElement2();
checkFirstPasswordLength2();
checkSecondPasswordLength2()
checkConfirmPassword2();
checkSubmit2();
