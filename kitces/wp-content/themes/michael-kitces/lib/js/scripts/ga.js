jQuery(document).ready(function() {
	if (typeof __gaTracker !== 'undefined' && __gaTracker) {
		// The following social links are in a footer widget on the site
		jQuery('.cgd-social-link.facebook a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'facebook');
		});
		jQuery('.cgd-social-link.twitter a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'twitter');
		});
		jQuery('.cgd-social-link.linkedIn a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'linkedin');
		});
		jQuery('.cgd-social-link.gplus a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'google-plus');
		});
		jQuery('.cgd-social-link.rss a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'rss-feed');
		});
		jQuery('.cgd-social-link.pinterest a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'pinterest');
		});
		jQuery('.cgd-social-link.youtube a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'youtube');
		});
		jQuery('.cgd-social-link.phone a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'phone');
		});
		jQuery('.cgd-social-link.email a').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'email');
		});

		// The following sharing buttons are on posts this is for while inline
		jQuery('.kitces-sharing.is-inline .shared-counts-button.twitter').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'twitter-jetpack');
		});
		jQuery('.kitces-sharing.is-inline .shared-counts-button.facebook').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'facebook-jetpack');
		});
		jQuery('.kitces-sharing.is-inline .shared-counts-button.linkedin').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'linkedin-jetpack');
		});
		jQuery('.kitces-sharing.is-inline .shared-counts-button.pinterest').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'pinterest-jetpack');
		});
		jQuery('.kitces-sharing.is-inline .shared-counts-button.email').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'email-jetpack');
		});
		jQuery('.kitces-sharing.is-inline .shared-counts-button.print.nopdf').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'print-jetpack');
		});
		jQuery('.kitces-sharing.is-inline .shared-counts-button.print.pdf').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'print-pdf');
		});

		// The following sharing buttons are on posts this is for while floating
		jQuery('.kitces-sharing.is-floating .shared-counts-button.twitter').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'twitter-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.facebook').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'facebook-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.google-plus-1').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'google-plus-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.linkedin').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'linkedin-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.pinterest').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'pinterest-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.email').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'email-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.print.nopdf').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'print-jetpack-floating');
		});
		jQuery('.kitces-sharing.is-floating .shared-counts-button.print.pdf').on('click', function() {
			__gaTracker('send', 'event', 'social', 'click', 'print-pdf-floating');
		});

		// Survey submit
		jQuery('.thank-you-survey-wrapper .survey-submit-actions .done-button').on('click', function() {
			__gaTracker('send', 'event', 'survey', 'submission', 'premiere-thankyou');
		});

		// Search for something in the graphics library
		jQuery('.graphics-library-search form.search-form').on('submit', function(e) {
			let searchTerm = jQuery(this)
				.find('.search-field')
				.val();
			if (searchTerm.length) {
				__gaTracker('send', 'event', 'Graphics Library', 'Search Submit', searchTerm);
			}
		});

		// Download an image from the graphics library
		jQuery('.graphics-library-post-image-meta a').on('click', function(e) {
			let fileURL = jQuery(this).attr('download');
			__gaTracker('send', 'event', 'Graphics Library', 'Download', fileURL);
		});

		// Member Resource Downloads
		jQuery('.post-6637 .entry-content a').on('click', function() {
			let fileURL = jQuery(this)
				.attr('href')
				.split('/')
				.pop();
			__gaTracker('send', 'event', 'Member Resources', 'Download', fileURL);
		});

		// Clicks on View PDF Newsletter in Members Dashboard
		jQuery('.objNewsTable tr a').on('click', function(e) {
			let fileURL = jQuery(this)
				.attr('href')
				.split('/')
				.pop();
			__gaTracker('send', 'event', 'Newsletter', 'Download', fileURL);
		});

		// Sidebar Clicks for advisors / for consumers
		jQuery('.mk-intro-widget a.more-link').on('click', function(e) {
			let moreLinkText = jQuery(this).text();
			if (moreLinkText.length) {
				__gaTracker('send', 'event', 'Sidebar', 'Services', moreLinkText);
			}
		});

		// Sidebar Clicks for What Michael's Reading Get it Now
		jQuery('.widget_what_michael_is_reading_widget a.more-link').on('click', function(e) {
			__gaTracker('send', 'event', 'Sidebar', 'Michaels Reading', 'Get Book Now');
		});

		// Sidebar Clicks for What Michael's Reading Full Book
		jQuery('.widget_what_michael_is_reading_widget .wmr-footer a.button').on('click', function(e) {
			__gaTracker('send', 'event', 'Sidebar', 'Michaels Reading', 'Full Book List');
		});

		// Sidebar Clicks for What Michael's Reading Full Book
		jQuery('.widget_what_michael_is_reading_widget .wmr-footer a.button').on('click', function(e) {
			__gaTracker('send', 'event', 'Sidebar', 'Michaels Reading', 'Full Book List');
		});

		// Sidebar Clicks for Signup Now CTA
		jQuery('.book.widget.widget_text.not-sticky .manual-optin-trigger.button').on('click', function(e) {
			__gaTracker('send', 'event', 'Sidebar', 'Fixed CTA', 'Sign Up Now');
		});

		// Sidebar Clicks for Signup Now CTA (floating)
		jQuery('.book.widget.widget_text.sticky-widget .manual-optin-trigger.button').on('click', function(e) {
			__gaTracker('send', 'event', 'Sidebar', 'Floating CTA', 'Sign Up Now');
		});

		// Sidebar Clicks for Popular Posts
		jQuery('.widget.widget_dpe_fp_widget a.recent-entries__link').on('click', function(e) {
			let entry = jQuery(this).parents('.entry');
			if (entry.length) {
				let title = entry.find('h4 .recent-entries__link');
				if (title.length) {
					let linkText = title.text().trim();
					__gaTracker('send', 'event', 'Sidebar', 'Popular Posts', linkText);
				}
			}
		});

		// Sidebar Clicks for Nerds Eye View Praise
		jQuery('.widget.widget_nerds_eye_view_praise_widget.not-sticky a').on('click', function(e) {
			let href = jQuery(this).attr('href');
			__gaTracker('send', 'event', 'Sidebar', 'Fixed NEV Praise', href);
		});

		// Sidebar Clicks for Nerds Eye View Praise (Floating)
		jQuery('.widget.widget_nerds_eye_view_praise_widget.sticky-widget a').on('click', function(e) {
			let href = jQuery(this).attr('href');
			__gaTracker('send', 'event', 'Sidebar', 'Floating NEV Praise', href);
		});

		// Sidebar Clicks for Out and About Full Schedule
		jQuery('.widget.widget_out_and_about_widget a.more-link').on('click', function(e) {
			__gaTracker('send', 'event', 'Sidebar', 'Out and About', 'Full Schedule');
		});

		/* After Header NEV Bar Click Tracking
        most of these are also targetin the mobile
        version of the same thing */

		// Select podcasts dropdown
		jQuery('.after-header-nev-inner .white-select.podcasts > a, .mobile-nav-wrap .mobile-menu-wrap li.podcasts > a').on('click', function(e) {
			__gaTracker('send', 'event', 'Subnav', 'Podcast Select', 'Podcasts');
		});

		// Click on a podcast in the dropdown
		jQuery('.after-header-nev-inner .white-select.podcasts .sub-menu a, .mobile-nav-wrap .mobile-menu-wrap li.podcasts .sub-menu a').on('click', function(e) {
			let link = jQuery(this);
			if (link.length) {
				let linkText = link.text().trim();
				__gaTracker('send', 'event', 'Subnav', 'Podcast Option', linkText);
			}
		});

		// Select blog categories dropdown
		jQuery('.after-header-nev-inner .white-select.blog-cats > a, .mobile-nav-wrap .mobile-menu-wrap li.blog-cats > a').on('click', function(e) {
			__gaTracker('send', 'event', 'Subnav', 'Category Select', 'Blog Categories');
		});

		// Click on a blog category in the dropdown
		jQuery('.after-header-nev-inner .white-select.blog-cats .sub-menu a, .mobile-nav-wrap .mobile-menu-wrap li.blog-cats .sub-menu a').on('click', function(e) {
			let link = jQuery(this);
			if (link.length) {
				let linkText = link.text().trim();
				__gaTracker('send', 'event', 'Subnav', 'Blog Option', linkText);
			}
		});

		// Search open
		jQuery('.after-header-nev-inner .blue-button.search.search-toggle, .mobile-nav-wrap .mobile-top-buttons .blue-button.open-search.mobile-search-toggle').on('click', function() {
			__gaTracker('send', 'event', 'Subnav', 'Search', 'Search Activate Button');
		});

		// Search close
		jQuery('.after-header-nev-inner .nev-right .search-wrap button.search-toggle, .mobile-nav-wrap .mobile-top-buttons .blue-button.close-search.mobile-search-toggle').on(
			'click',
			function() {
				__gaTracker('send', 'event', 'Subnav', 'Search', 'Search Close Button');
			}
		);

		// Advanced Search Toggle
		jQuery('.after-header-nev-inner .nev-right .search-wrap button.advanced-toggle, .mobile-nav-wrap .advanced-toggle').on('click', function() {
			__gaTracker('send', 'event', 'Advanced Search', 'Activate', 'Activate');
		});

		// Search for something
		jQuery('.after-header-nev-inner .nev-right .search-wrap form.search-form, .mobile-search-wrap form.search-form').on('submit', function(e) {
			let searchForm = jQuery(this);
			let searchTerm = searchForm.find('.search-form-input').val();

			let advancedForm = searchForm.find('.search-advanced.opened');

			if (advancedForm.length) {
				let advancedSearchTerm = advancedForm
					.find('input.search-form-term')
					.val()
					.trim();
				let advancedAuthor = advancedForm
					.find('#advanced-author option:selected')
					.text()
					.trim();
				let advancedCategory = advancedForm
					.find('#advanced-category option:selected')
					.text()
					.trim();
				let advancedDateFrom = advancedForm
					.find('.date-range .datepicker.from-date')
					.val()
					.trim();
				let advancedDateTo = advancedForm
					.find('.date-range .datepicker.to-date')
					.val()
					.trim();

				if (advancedDateFrom.length) {
					__gaTracker('send', 'event', 'Advanced Search', 'From Date', advancedDateFrom);
				} else {
					__gaTracker('send', 'event', 'Advanced Search', 'From Date', 'Not Set');
				}

				if (advancedDateTo.length) {
					__gaTracker('send', 'event', 'Advanced Search', 'To Date', advancedDateTo);
				} else {
					__gaTracker('send', 'event', 'Advanced Search', 'To Date', 'Not Set');
				}

				__gaTracker('send', 'event', 'Advanced Search', 'Search', advancedSearchTerm);
				__gaTracker('send', 'event', 'Advanced Search', 'Author', advancedAuthor);
				__gaTracker('send', 'event', 'Advanced Search', 'Category', advancedCategory);
			} else if (searchTerm.length) {
				__gaTracker('send', 'event', 'Subnav', 'Search', searchTerm);
			}
		});

		// After Header NEV Member Login
		jQuery('.after-header-nev-inner .member-login a, body:not(.logged-in) .mobile-nav-wrap .mobile-top-buttons .green-button').on('click', function(e) {
			__gaTracker('send', 'event', 'Subnav', 'Login', 'Member Login');
		});

		// After Header NEV Member Home
		jQuery('.after-header-nev-inner .member-home a, body.logged-in .mobile-nav-wrap .mobile-top-buttons .green-button').on('click', function(e) {
			__gaTracker('send', 'event', 'Subnav', 'Member', 'Member Home');
		});

		// Podcast subscribe button clicks
		jQuery('.subscribe-section .podcast-image-links-wrap a').on('click', function(e) {
			let link = jQuery(this);
			if (link.length) {
				let linkText = link.attr('title').trim();
				if (linkText.length) {
					__gaTracker('send', 'event', 'Podcast', 'Subscribe', linkText);
				}
			}
		});

		jQuery('.subscribe-section .subscribe-email-form-wrap > form').on('submit', function(e) {
			__gaTracker('send', 'event', 'Podcast', 'Subscribe', 'Podcast Email Updates');
		});

		// Advisor Articles
		jQuery('article.advisor-news .entry-title > a').on('click', function(e) {
			let link = jQuery(this);
			if (link.length) {
				let articleTitle = link.data('title').trim();

				if (articleTitle.length) {
					__gaTracker('send', 'event', 'Advisor News', articleTitle, 'Title Click');
				}
			}
		});

		jQuery('article.advisor-news .advisor-news-social > a').on('click', function(e) {
			let link = jQuery(this);
			if (link.length) {
				let articleHeader = link.parents('.entry-header');
				let articleTitleLink = articleHeader.find('.entry-title-link');
				let articleTitle = articleTitleLink.data('title').trim();
				let shareDest = link.attr('title').trim();

				if (articleTitle.length && shareDest.length) {
					__gaTracker('send', 'event', 'Advisor News', articleTitle, shareDest);
				}
			}
		});

		jQuery('article.advisor-news .commentary-trigger').on('click', function(e) {
			let button = jQuery(this);
			if (button.length) {
				let articleHeader = button.parents('.entry-header');
				let articleTitleLink = articleHeader.find('.entry-title-link');
				let articleTitle = articleTitleLink.data('title').trim();
				let buttonText = button.text();

				if (buttonText.length && articleTitle.length) {
					__gaTracker('send', 'event', 'Advisor News', articleTitle, buttonText);
				}
			}
		});

		// Weekend category links
		jQuery('a[data-wknd-cat]').on('click', function(e) {
			let theLink = jQuery(this);
			if (theLink.length) {
				let category = theLink.data('wknd-cat');
				let href = theLink.attr('href');
				if (href.length && category.length) {
					__gaTracker('send', 'event', 'Weekend Reading Link', category, href);
				}
			}
		});

		// Conference Reading Materials Title
		jQuery('.conference-reading-materials a').on('click', function(e) {
			let theLink = jQuery(this);
			if (theLink.length) {
				var readingListWrap = theLink.parents('.reading-list-wrap');
				let readingListTitle = readingListWrap.find('p.post-list-title');
				let href = theLink.attr('href');

				if (!readingListTitle.length) {
					readingListTitle = 'No Post List';
				} else {
					readingListTitle = readingListTitle.text();
				}

				if (href.length && readingListTitle.length) {
					__gaTracker('send', 'event', 'Events Page', readingListTitle, href);
				}
			}
		});

		// Join Your Fellow Advisors hero button
		jQuery('.button.homepage-above-fold-optin').on('click', function(e) {
			__gaTracker('send', 'event', 'Home Page', 'Hero CTA', 'Join');
		});

		// In post nav link clicks
		jQuery('#inpost-nav-wrap .h2-link, #inpost-nav-wrap .h3-link').on('click', function(e) {
			let theLink = jQuery(this);
			if (theLink.length) {
				var navWrap = theLink.parents('#inpost-nav-wrap');
				let postTitle = navWrap.data('post-title');
				let linkText = theLink.text();

				if (postTitle.length && linkText.length) {
					__gaTracker('send', 'event', 'Left Sidebar', postTitle + ' Navigation', linkText);
				}
			}
		});

		// Kitces Gutenberg Block Event Tracking of Links
		jQuery('.mk-block a, .mk-modal-inner-inner a').on('click', function(e) {
			let theLink = jQuery(this);
			if (theLink.length) {
				let contentWrap = jQuery('article.entry');
				let pageTitle = '';

				if (contentWrap.length) {
					pageTitle = contentWrap.attr('aria-label');
				}

				let modalOuter = theLink.parents('.mk-modal-inner-inner');
				let blockOuter = null;

				if (modalOuter.length) {
					blockOuter = modalOuter;
				} else {
					blockOuter = theLink.parents('.mk-block');
				}

				let sectionName = blockOuter.data('mk-block-event-action');
				let href = theLink.attr('href');

				if (pageTitle.length && sectionName.length && href.length) {
					__gaTracker('send', 'event', pageTitle, sectionName, href);
				}
			}
		});

		// Favorite Post Clicks - Login to save
		// Note, saving and unsaving happen in the JS code in the plugin
		jQuery('.kitces-favorite-posts__text.logged-out a').on('click', function(e) {
			let theLink = jQuery(this);
			let linkActionLabel = 'Login';
			if (theLink.length) {
				var navWrap = jQuery('#inpost-nav-wrap');
				let postTitle = navWrap.data('post-title');
				if (theLink.hasClass('create-account')) {
					linkActionLabel = 'Create Account';
				}

				if (postTitle.length && linkActionLabel.length) {
					__gaTracker('send', 'event', 'Article Save', linkActionLabel, postTitle);
				}
			}
		});

		// Favorite Post Table Clicks - View Article
		jQuery('.kitces-saved-articles .kitces-saved-articles__post a').on('click', function(e) {
			let theLink = jQuery(this);
			if (theLink.length) {
				let postTitle = theLink.text();

				if (postTitle.length) {
					__gaTracker('send', 'event', 'Saved Article Access', 'Click', postTitle);
				}
			}
		});

		// Cross Domain Tracking
		(function() {
			/*
			 * This is required to pass the linker parameter "_ga" to Chargebee, which
			 * in turn could be sent to the Google to track vistors across multiple
			 * domains. See https://goo.gl/jwqXvw to understand about the _ga query
			 * parameter.
			 */

			__gaTracker('require', 'linker');

			function addListener(element, type, callback) {
				if (element.addEventListener) {
					element.addEventListener(type, callback);
				} else if (element.attachEvent) {
					element.attachEvent('on' + type, callback);
				}
			}

			/*
			 * This function decorates the link's URL with the _ga parameter. This
			 * uses Google's directive to modify the link with the _ga parameter.
			 * See https://goo.gl/YVZBMG
			 */

			function decorateMe(event) {
				event = event || window.event; // Cross browser hoops.
				var target = event.target || event.srcElement;
				if (target && target.href) {
					// Ensure this is a link.
					__gaTracker('linker:decorate', target);
				}
			}

			/*
			 * Here, this event listener, listens to the user's click event and
			 * automatically calls the decorate function to append the _ga param
			 * to Chargebee's hosted page URL. If you are redirecting users to
			 * Chargebee's hosted pages via javascript, you have to call the decorate
			 * function before that.  See https://goo.gl/obO8Hs.
			 */
			addListener(window, 'load', function() {
				for (var i = 0; i < document.links.length; i++) {
					var dLink = document.links[i].href;

					if ((dLink && dLink.indexOf('chargebee.com/hosted_pages/plans') >= 0) || dLink.indexOf('chargebee.com/pages/') >= 0) {
						addListener(document.links[i], 'click', decorateMe);
					}
				}
			});
		})();
	}
});

window.mk_ga_track_js_event = function(category, action, label) {
	if (typeof __gaTracker !== 'undefined' && __gaTracker) {
		if (category.length && action.length && label.length) {
			__gaTracker('send', 'event', category, action, label);
		}
	}
};
