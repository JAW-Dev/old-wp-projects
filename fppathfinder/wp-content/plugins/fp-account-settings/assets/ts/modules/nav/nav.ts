import onChange from './onChange';

const nav = () => {
	const mediaQuery = window.matchMedia('(min-width: 640px)');

	mediaQuery.addListener(onChange);
	onChange(mediaQuery);
};

export default nav;
