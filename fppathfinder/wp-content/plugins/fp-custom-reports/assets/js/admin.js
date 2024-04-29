// Import Modules
import report from './modules/report';

report({
	filename: 'favorites-report',
	target: 'favorites-report',
	action: 'favorites_report'
});

report({
	filename: 'member-report',
	target: 'member-report',
	action: 'member_report'
});

report({
	filename: 'pdfs-report',
	target: 'pdfs-report',
	action: 'pdfs_report'
});

report({
	filename: 'pdf-bundles-report',
	target: 'pdf-bundles-report',
	action: 'pdf_bundles_report'
});
