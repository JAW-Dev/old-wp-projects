// import passwordMeter from './passwordMeter';
import PasswordValidator from 'password-validator';
import showTooltip from './showTooltip';
import validatePassword from './validatePassword';

const inputHandler = () => {
	const pass1 = <HTMLInputElement>document.getElementById('password-1');
	const pass2 = <HTMLInputElement>document.getElementById('password-2');

	const pass1Tooltip = <HTMLElement>document.getElementById('password-reset-form-tooltip-pass-1');
	const pass2Tooltip = <HTMLElement>document.getElementById('password-reset-form-tooltip-pass-2');
	const tooltips = Array.from(document.querySelectorAll('.password-reset-form__tooltip'));

	const passInputs = <HTMLInputElement[]>[pass1, pass2];
	const passInputTooltips = <HTMLElement[]>[pass1Tooltip, pass2Tooltip];

	showTooltip(passInputs, passInputTooltips, tooltips);
	validatePassword(passInputs);
};

export default inputHandler;
