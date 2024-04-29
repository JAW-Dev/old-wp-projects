const lowercaseDash = string => {
	return string.replace(/_/g, ' ').replace(/-/g, ' ').toLowerCase().split(' ').map(word => word).join('-');
}

module.exports = lowercaseDash;