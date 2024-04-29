import clickHandler from './clickHandler';
import mobileTabs from './mobileTabs';

const accordian = () => {
	const boxes = Array.from(document.querySelectorAll('.accordian-wrap'));

	boxes.forEach(box => {
		clickHandler(box);
	});

	mobileTabs();
};

export default accordian;
