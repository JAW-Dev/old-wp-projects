// Import Modules
import report from './modules/report';

report({
	filename: 'quiz-time-average-report',
	target: 'quiz-time-average-report',
	action: 'quiz_time_average_report'
});

report({
	filename: 'quiz-time-raw-report',
	target: 'quiz-time-raw-report',
	action: 'quiz_time_raw_report'
});
