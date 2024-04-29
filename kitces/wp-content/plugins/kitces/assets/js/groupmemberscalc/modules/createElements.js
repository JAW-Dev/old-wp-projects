// Import modules
import { isEmpty } from '../../modules/utils/utils';

const createElements = () => {
	return new Promise(resolve => {
		try {
			const button = document.querySelector('.group-intake .gform_footer');

			if (!isEmpty(button)) {
				const calcWrapper = document.createElement('div');
				calcWrapper.classList.add('member-calculator');
				calcWrapper.setAttribute('id', 'member-calculator');

				button.before(calcWrapper);

				const calcLabel = document.createElement('span');
				calcLabel.classList.add('member-label');
				calcLabel.setAttribute('id', 'member-label');
				calcLabel.innerHTML = 'Estimated Total: ';

				calcWrapper.appendChild(calcLabel);

				const calcTotal = document.createElement('span');
				calcTotal.classList.add('member-total');
				calcTotal.setAttribute('id', 'member-total');

				calcLabel.appendChild(calcTotal);

				const calcSavingsLabel = document.createElement('span');
				calcSavingsLabel.classList.add('member-savings');
				calcSavingsLabel.setAttribute('id', 'member-savings');
				calcSavingsLabel.setAttribute('style', 'margin-left: 2rem;');
				calcSavingsLabel.innerHTML = 'Savings: ';

				calcWrapper.appendChild(calcSavingsLabel);

				const calcSavingsTotal = document.createElement('span');
				calcSavingsTotal.classList.add('member-savings-total');
				calcSavingsTotal.setAttribute('id', 'member-savings-total');

				calcSavingsLabel.appendChild(calcSavingsTotal);

				setTimeout(async () => {
					resolve();
				});
			}
		} catch (err) {
			console.error(err);
		}
	});
};

export default createElements;
