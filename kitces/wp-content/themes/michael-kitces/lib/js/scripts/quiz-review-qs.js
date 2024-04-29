const hideQuiz = function( gform, gformPre, takeQuizButton, quizReviewWrap, quizCCI ) {
    gform.hide();
    gformPre.hide();
};

const displayQuizOnly = function( gform, gformPre, takeQuizButton, quizReviewWrap, quizCCI, slideTo, target ) {
    takeQuizButton.slideUp();
    quizReviewWrap.slideUp();
    quizCCI.slideUp();
    gform.slideDown();
    gformPre.slideDown();    

    if ( slideTo ) {
        setTimeout(function(){
            jQuery('html, body').stop().animate({
                scrollTop: target.offset().top - 128
            }, 800);
          }, 800);
          jQuery('html, body').stop();
    }

};

jQuery(document).ready(function () {
    let gform = jQuery('.gform_wrapper');
    let gformPre = jQuery('.before-quiz-note');
    let takeQuizButton = jQuery('button.take-quiz-btn');
    let quizReviewWrap = jQuery('.quiz-review-sc-wrap');
    let quizCCI = jQuery('.accordion-block.quiz-cci');
    let gformError = gform.find('.gfield_error');
    let gformConfirmation = jQuery('#gquiz_confirmation_message');
    let gformInConfirmation = jQuery('#gquiz_confirmation_message .gform_wrapper');

    if ( gformError.length ) {
        displayQuizOnly( gform, gformPre, takeQuizButton, quizReviewWrap, quizCCI, true, gform );
    } else if(  gformInConfirmation.length ) {
        displayQuizOnly( gform, gformPre, takeQuizButton, quizReviewWrap, quizCCI, true, gformConfirmation );
    } else {
        if ( ! gform.length && takeQuizButton.length ) {
            takeQuizButton.hide();
        }
        
        if ( gform.length && takeQuizButton.length ) {
            hideQuiz( gform, gformPre, takeQuizButton, quizReviewWrap, quizCCI );
        }
    
        takeQuizButton.on('click', function() {
            displayQuizOnly( gform, gformPre, takeQuizButton, quizReviewWrap, quizCCI, true, gform );
        });
    }
});