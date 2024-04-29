const insertAfter = (referenceNode: HTMLElement, newNode: HTMLElement) => {
	referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
};

export default insertAfter;
