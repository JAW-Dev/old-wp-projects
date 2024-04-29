// Checks for OIM top optin and tweaks mobile menu top accordingly
var oimTopCheck = function(jqueryElements) {
	setTimeout(function() {
		var htmlBody = jQuery('html');
		var htmlBodyPadTop = parseInt(htmlBody.css('padding-top'));

		if (htmlBodyPadTop > 0) {
			var siteHead = jQuery('.site-header');
			var siteHeadTall = siteHead.outerHeight();
			var topMeasure = htmlBodyPadTop + siteHeadTall;
			jQuery('.mobile-nav-wrap').css('top', topMeasure);
		}
	}, 800);
};

// Trigger oimTopCheck when a Campaign Shows Up
document.addEventListener('om.Campaign.afterShow', function(event) {
	console.log('OM Campaign Show');
	oimTopCheck();
});

// Clean Up After oim closes
document.addEventListener('om.Campaign.afterClose', function(event) {
	console.log('OM Campaign Close');
	jQuery('.mobile-nav-wrap').css('top', '');
});

// Legacy oimTopCheck/Close
jQuery(document).ready(function($) {
	jQuery(document).on('OptinMonsterOnShow', function(event, data, object) {
		console.log('OM Campaign Show - Legacy');
		oimTopCheck();
	});

	jQuery(document).on('OptinMonsterOnClose', function(event, data, object) {
		console.log('OM Campaign Close');
		jQuery('.mobile-nav-wrap').css('top', '');
	});
});

// On Scroll lets set up the floating site header
jQuery(document).ready(function() {
	jQuery(window).scroll(function(e) {
		var header = jQuery('header.site-header');
		var siteContainer = jQuery('.site-container');
		var adminBar = jQuery('#wpadminbar');
		var omFloat = jQuery('html.om-position-floating-top');
		if (!omFloat.length) {
			var omFloat = jQuery('.top-bar-opt');
		}

		if (header.length && siteContainer.length && !adminBar.length && !omFloat.length) {
			headerHeight = header.outerHeight();
			header.addClass('is-fixed');
			siteContainer.css('padding-top', headerHeight + 'px');
		} else {
			header.removeClass('is-fixed');
			siteContainer.css('padding-top', 0);
		}
	});
});

// Mobile Nav Trigger
jQuery(document).ready(function() {
	jQuery('.nav-trigger').on('click', function(e) {
		e.preventDefault();
		jQuery('body').toggleClass('mobile-menu-is-open');
		oimTopCheck();
	});

	jQuery('.mobile-search-toggle').on('click', function(e) {
		e.preventDefault();
		jQuery('.mobile-search-toggle').toggle();
		jQuery('.mobile-search-wrap').slideToggle();
		jQuery('.mobile-search-wrap .search-form-input').focus();
	});

	jQuery('.mobile-menu-wrap .menu-item.menu-item-has-children > a, .member-sidebar-menu-wrap .menu-item.menu-item-has-children > a').on('click', function(e) {
		e.preventDefault();
		let menuItem = jQuery(this).parent('.menu-item');

		menuItem.toggleClass('is-open');
		menuItem.find('> .sub-menu').slideToggle();
	});

	jQuery('.member-sidebar-toggle').on('click', function(e) {
		let memSidebarToggle = jQuery(this);

		if (memSidebarToggle !== undefined) {
			let memSidebar = memSidebarToggle.parent('.member-sidebar');
			let memSidebarInner = memSidebar.find('.member-sidebar-inner');

			memSidebar.toggleClass('is-open');
			memSidebarInner.slideToggle();
		}
	});

	// Check to see if menu goes outside of container - add a class if it is
	jQuery('.genesis-nav-menu .menu-item').on('mouseenter mouseleave', function(e) {
		if (jQuery(this).find('.sub-menu').length) {
			var elm = jQuery(this).children('.sub-menu');
			var off = elm.offset();
			var l = off.left;
			var w = elm.width();
			var docW = jQuery('body').width();

			var isEntirelyVisible = l + w <= docW;

			if (!isEntirelyVisible && docW >= 1600) {
				isEntirelyVisible = true;
			}

			if (!isEntirelyVisible) {
				console.log('inside menu edge');
				jQuery(this)
					.children('.sub-menu')
					.addClass('edge');
			} else {
				jQuery(this)
					.children('.sub-menu')
					.removeClass('edge');
			}
		}
	});

	jQuery('.nev-menu-wrap .menu-item.white-select > a').on('click', function(e) {
		e.preventDefault();

		if (jQuery(this).parents('.menu-item.active').length >= 1) {
			jQuery('.menu-item').removeClass('active');
			jQuery('body').removeClass('white-select-active');
		} else {
			jQuery('.menu-item').removeClass('active');
			jQuery('body').removeClass('white-select-active');
			jQuery(this)
				.parent('.menu-item')
				.addClass('active');
			jQuery('body').addClass('white-select-active');
		}
	});

	jQuery('*').on('click', function(e) {
		var selectOpen = jQuery('body').hasClass('white-select-active');

		if (selectOpen) {
			e.stopPropagation();
			if (jQuery(this).parents('.white-select.menu-item.active').length === 0) {
				jQuery('.menu-item').removeClass('active');
				jQuery('body').removeClass('white-select-active');
			}
		}
	});
});
