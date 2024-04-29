import JSZip from 'jszip';
import { saveAs } from 'file-saver';

const zipHandler = (data, percentage) => {
	if (percentage === 100) {
		const zip = JSZip();

		// data.pdfs.forEach(pdf => {
		// 	const blob = base64toBlob(pdf.path, 'application/pdf');
		// 	zip.file(pdf.name, blob);
		// });

		// zip.generateAsync({ type: 'blob' }).then(zipFile => {
		// 	saveAs(zipFile, data.bundle_name);
		// });

		const folder = zip.folder(data.bundle_name);

		// data.pdfs.map(pdf => {
		// 	return zip.file(pdf.name, pdf.path);
		// });

		data.pdfs.forEach(pdf => {
			folder.file(pdf.name, pdf.path);
		});

		zip.generateAsync({ type: 'blob' }).then(content => {
			saveAs(content, data.bundle_name);
		});

		// zip.generateAsync({ type: 'base64' }).then(content => {
		// 	location.href = `data:application/zip;base64,${content}`;
		// });
	}
};
export default zipHandler;
