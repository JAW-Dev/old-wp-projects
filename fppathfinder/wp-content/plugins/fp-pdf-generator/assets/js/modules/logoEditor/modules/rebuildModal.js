// Import Modules
import createModal from './createModal'
import { ifElementExists, isObjectEmpty } from './../helpers/index'

const rebuildModal = (args, imgSrc = '') => {

	// Bail if args is empty
	if (isObjectEmpty(args)) {
		return
	}

	const target = document.querySelector('.' + args.fileUploadWrap).parentNode
	const child = document.getElementById(args.modal)

	// Remove existing modal
	if (ifElementExists(target) && ifElementExists(child)) {
		target.removeChild(child);
	}

	createModal(args, imgSrc)
}

export default rebuildModal
