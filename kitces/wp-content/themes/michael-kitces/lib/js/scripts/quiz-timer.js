const getTime = () => {
	const date = new Date();
	let hour = date.getHours();
	let minutes = date.getMinutes();
	let session = 'am';

	if (hour >= 12) {
		session = 'pm';
	}

	if (hour > 12) {
		hour -= 12;
		session = 'pm';
	} else if (hour === 0) {
		hour = 12;
	}

	minutes = minutes < 10 ? '0' + minutes : minutes;

	const time = hour + ':' + minutes + session;
	const text = document.querySelectorAll('.quiz-timer-time');

	text.forEach(item => {
		item.innerText = time;
		item.textContent = time;
	});

	setTimeout(getTime, 1000);
};

const getDate = () => {
	const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

	const dateNow = new Date();
	const yearNow = dateNow.getFullYear();
	const monthNow = months[dateNow.getMonth()];
	const dayNow = dateNow.getDate();
	let daySuffix;

	switch (dayNow) {
		case 1:
		case 21:
		case 31:
			daySuffix = 'st';
			break;
		case 2:
		case 22:
			daySuffix = 'nd';
			break;
		case 3:
		case 23:
			daySuffix = 'rd';
			break;
		default:
			daySuffix = 'th';
			break;
	}

	const date = `${monthNow} ${dayNow}${daySuffix}, ${yearNow}`;
	const text = document.querySelectorAll('.quiz-timer-date');

	text.forEach(item => {
		item.innerText = date;
		item.textContent = date;
	});
};

const beforeQuiz = () => {
	const beforeQuizNote = document.querySelector('.before-quiz-note');
	const timerWrapper = document.createElement('div');
	timerWrapper.setAttribute('id', 'quiz-timer');
	timerWrapper.setAttribute('class', 'quiz-timer');
	timerWrapper.innerHTML = `The current time is <strong><span class="quiz-timer-time"></span> (Eastern Time Zone) on <span class="quiz-timer-date"></span></strong>. Kitces reports CE credit based on the United States' Eastern Time Zone`;

	if (beforeQuizNote) {
		beforeQuizNote.appendChild(timerWrapper);
	}
};

const quizTimer = () => {
	beforeQuiz();
	getTime();
	getDate();
};

quizTimer();
