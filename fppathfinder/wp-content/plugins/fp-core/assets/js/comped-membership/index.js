const checkbox = document.getElementById('rcp-comped-membership');
let initial;
let recurring;

setTimeout(() => {
	initial = document.getElementById('rcp-initial-amount').value;
	recurring = document.getElementById('rcp-recurring-amount').value;
}, 1000);

checkbox.addEventListener('change', () => {
	const initialNew = document.getElementById('rcp-initial-amount');
	const recurringNew = document.getElementById('rcp-recurring-amount');

	if (checkbox.checked) {
		initialNew.value = 0;
		recurringNew.value = 0;
	} else {
		initialNew.value = initial;
		recurringNew.value = recurring;
	}
});
