/* global document, jQuery, window */

jQuery(document).ready(function() {
	const primarySidebar = jQuery('.sidebar-primary');
	const contentContainer = jQuery('.content-sidebar-wrap .content');
	const theWindow = jQuery(window);

	if (primarySidebar.length > 0 && contentContainer.length > 0) {
		if (primarySidebar.height() < contentContainer.height()) {
			jQuery(window).on('scroll', function() {
				if (theWindow.width() > 946) {
					let closedSidebar = jQuery('.content-sidebar-wrap.closed-sidebar');

					if (!closedSidebar.length > 0) {
						const firstStickyWidgetHeight = jQuery('.sticky-widget')
							.first()
							.height();
						const secondStickyWidgetCssPosition = firstStickyWidgetHeight + 120;
						jQuery('.sticky-widget')
							.last()
							.css('top', `${secondStickyWidgetCssPosition}px`);

						const lastWidget = jQuery('.sticky-widget')
							.first()
							.prev();
						const lastWidgetPosition = lastWidget.position().top + lastWidget.outerHeight(true);
						const contentSidebarWrapPosition = jQuery('.content-sidebar-wrap').offset().top + jQuery('.content-sidebar-wrap').outerHeight(true);
						const contentPosition = contentSidebarWrapPosition - 190;
						const stickyWidgetBottomPosition = 344 + 347;
						const windowScrollPosition = stickyWidgetBottomPosition + jQuery(window).scrollTop();

						jQuery('.sticky-widget').each(function() {
							if (jQuery(window).scrollTop() > lastWidgetPosition) {
								jQuery(this).addClass('is-sticky');
							} else {
								jQuery(this).removeClass('is-sticky');
							}
						});

						if (windowScrollPosition > contentPosition) {
							jQuery('.sticky-widget').addClass('is-sticky-on-bottom');
						} else {
							jQuery('.sticky-widget').removeClass('is-sticky-on-bottom');
						}
					}
				}
			});
		}
	}
});
