import modaal from 'modaal';

const ajaxHandler = (email, nonce, plan, member, modal, iic = 'false') => {
	jQuery.ajax({
		type: 'post',
		url: kitcesData.adminAjax,
		data: {
			action: 'email_lookup',
			nonce,
			email,
			plan,
			modal,
			iic
		},
		success: response => {
			if (response === 'true') {
				const body = jQuery('.signup-email-submit__body');
				const message = jQuery('.signup-email-submit__existing-message');

				body.addClass('hide');
				message.addClass('show');
			} else {
				const json = JSON.parse(response);
				const cbInstance = Chargebee.getInstance();
				const cart = cbInstance.getCart();
				const product = cbInstance.initializeProduct(json.plan);

				cart.replaceProduct(product);
				cart.setCustomer({ email: `${json.email}` });

				if (json.iic === 'true') {
					product.addAddon({ id: 'inside-info', quantity: 1 });
				}

				jQuery(`.${json.modal}`).modaal('close');
				cart.proceedToCheckout();
			}
		},
		fail: err => {
			console.error(`There was an error: ${err}`);
		}
	});
};

export default ajaxHandler;
