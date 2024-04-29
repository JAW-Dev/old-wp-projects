jQuery(document).on('keypress', '.gform_wrapper', function(e) {
  var code = e.keyCode || e.which;
  if (
    code == 13 &&
    !jQuery(e.target).is('textarea,input[type="submit"],input[type="button"]')
  ) {
    e.preventDefault();
    console.log('Form Not Submitted');
    return false;
  }
});

jQuery(document).ready(function() {
  var gfAnchorDiv = jQuery('#gf-anchoring');
  var gfAnchorID = jQuery(gfAnchorDiv).attr('data-gf-conf-id');
  var target = jQuery('#' + gfAnchorID);

  if (gfAnchorID && target && gfAnchorID.length && target.length) {
    event.preventDefault();
    jQuery('html, body')
      .stop()
      .animate(
        {
          scrollTop: target.offset().top - 32
        },
        1000
      );
  }
});
