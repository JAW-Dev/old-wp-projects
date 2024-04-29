jQuery(document).ready(function() {

	// Accordion
	jQuery('.accordion-title').on('click', function() {
        jQuery('.accordion-info').slideUp(300);
        if (jQuery(this).hasClass('active')){
            jQuery('.accordion-title').removeClass("active");
        } else {
            jQuery('.accordion-title').removeClass("active");
            jQuery(this).next('.accordion-info').slideDown(300);
            jQuery(this).addClass("active");
            return false;
        }
    });

    jQuery('.question-answer-choice').on('click', function(e) {
        e.preventDefault();
        jQuery(this).next('.question-answer-explanation').slideDown(300);
    });

});
