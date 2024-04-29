jQuery(document).ready(function() {
    jQuery('.author-block__blurb__more').on('click', function() {
        var blurb = jQuery(this).parents('.author-block');
        blurb.addClass('open');
    });
});