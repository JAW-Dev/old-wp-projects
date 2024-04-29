import insertAfter from './insertAfter';

const rcpInputWithSpan = () => {
	const forms = document.querySelectorAll('#tabs-group-settings .rcp_form');

	forms.forEach(form => {
		const formControlls = form.querySelectorAll('.form-control');

		if (formControlls.length === 0) {
			return false;
		}

		formControlls.forEach(formControll => {
			const span = formControll.querySelector('span');
			const label = formControll.querySelector('label');
			const input = formControll.querySelector('input');

			if (span) {
				const wrap = document.createElement('div');
				wrap.classList.add('input-wrap');
				insertAfter(label, wrap);
				wrap.appendChild(input);
				wrap.appendChild(span);
			}
		});
	});
};

export default rcpInputWithSpan;
