const SlimSelect = require('slim-select');

jQuery(document).ready(function() {
	let courseCatWrap = jQuery('.course-catalog-section-wrap');
	let quizListWrap = jQuery('.kitces-quiz-list');
	let coursesFilterOuterWrap = jQuery('.courses-filter-outer-wrap');
	let noResultsTable = jQuery('.subpages-no-results');

	const checkCourseCatResults = function() {
		let accordionBlocks = jQuery('.course-catalog-section .accordion-block');

		if (accordionBlocks.length) {
			accordionBlocks.each(function() {
				let block = jQuery(this);
				let noResults = block.children('.no-results');
				let visibleRows = block.children('.accordion-row.visible');

				if (visibleRows.length > 0) {
					noResults.slideUp();
				} else {
					noResults.slideDown();
				}
			});
		}
	};

	const checkSubPagesResults = function() {
		if (quizListWrap.length) {
			let visibleRows = quizListWrap.find('tr.visible');

			if (visibleRows.length > 0) {
				noResultsTable.slideUp();
			} else {
				noResultsTable.slideDown();
			}
		}
	};

	const afterQuizCatFilterSelect = function() {
		coursesFilterOuterWrap.slideDown();
	};

	const pollForFilterLoaded = function() {
		const el = jQuery('.ss-main');

		if (el.length) {
			afterQuizCatFilterSelect();
		} else {
			setTimeout(pollForFilterLoaded, 300);
		}
	};

	if ((courseCatWrap.length || quizListWrap.length) && coursesFilterOuterWrap.length) {
		let quizTypeFilterSelect = jQuery('.quiz-type-filter-select');

		new SlimSelect({
			select: '.quiz-cat-filter-select',
			placeholder: 'Category(s)'
		});

		if (quizTypeFilterSelect.length) {
			new SlimSelect({
				select: '.quiz-type-filter-select',
				placeholder: 'CE Type(s)'
			});
		}

		if (noResultsTable.length) {
			noResultsTable.slideUp();
		}

		pollForFilterLoaded();
	}

	jQuery('.quiz-cat-filter-select, .quiz-type-filter-select').on('change', function() {
		let cats = jQuery('.quiz-cat-filter-select').val();
		let types = jQuery('.quiz-type-filter-select').val();
		let filterItems = [];

		if (cats.length && types === undefined) {
			filterItems = cats;
		} else if (types.length && cats === undefined) {
			filterItems = types;
		} else {
			filterItems = [...cats, ...types];
		}

		let classString = '';
		if (filterItems.length) {
			classString = '.' + filterItems.join('.');
		}

		if (courseCatWrap.length) {
			if (filterItems.length > 0) {
				courseCatWrap.find('.accordion-row' + classString).addClass('visible');
				courseCatWrap.find('.accordion-row' + classString).slideDown();
				courseCatWrap.find('.accordion-row:not(' + classString + ')').removeClass('visible');
				courseCatWrap.find('.accordion-row:not(' + classString + ')').slideUp();
			} else {
				courseCatWrap.find('.accordion-row').addClass('visible');
				courseCatWrap.find('.accordion-row').slideDown();
			}

			checkCourseCatResults();
		}

		if (quizListWrap.length) {
			if (filterItems.length > 0) {
				quizListWrap.find('tbody tr' + classString).addClass('visible');
				quizListWrap.find('tbody tr' + classString).slideDown();
				quizListWrap.find('tbody tr:not(' + classString + ')').removeClass('visible');
				quizListWrap.find('tbody tr:not(' + classString + ')').slideUp();
			} else {
				quizListWrap.find('tbody tr').addClass('visible');
				quizListWrap.find('tbody tr').slideDown();
			}

			checkSubPagesResults();
		}
	});
});
