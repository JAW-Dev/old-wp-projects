import Cookies from 'js-cookie';

const ratingsType = () => {
	const words = ['star', 'nerd'];
	const rand = Math.floor(Math.random() * words.length);
	const cookie = Cookies.get('kitces-post-rating-type');

	if (!cookie) {
		Cookies.set('kitces-post-rating-type', words[rand], {
			expires: 365,
			domian: window.location.host
		});
	}
};

export default ratingsType;
