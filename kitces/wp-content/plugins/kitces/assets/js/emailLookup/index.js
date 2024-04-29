import comboPkgClickHandler from './modules/comboPkgClickHandler';
import onSubmitHandler from './modules/onSubmitHandler';
import linkClickHandler from './modules/linkClickHandler';

const emailLookupInit = () => {
	comboPkgClickHandler();
	onSubmitHandler();
	linkClickHandler();
};

export default emailLookupInit;
