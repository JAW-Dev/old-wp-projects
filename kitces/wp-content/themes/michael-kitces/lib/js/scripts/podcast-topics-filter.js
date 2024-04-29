var paginatePodcastBlocks = function( activeClass ) {
    var podcastBlocksActive = jQuery('.podcast-topics-posts-outer .podcast-topics-post.' + activeClass);
    var podcastBlocksAll = jQuery('.podcast-topics-posts-outer .podcast-topics-post');


    if ( podcastBlocksActive.length ) {

        podcastBlocksAll.hide();
        podcastBlocksActive.show();

        var items = podcastBlocksActive;
        var numItems = items.length;
        var perPage = 6;
    
        // Only show the first 6 (or first `per_page`) items initially.
        items.slice(perPage).hide();
    
        // Now setup the pagination using the `.pagination-page` div.
        jQuery(".podcast-episodes-pagination").pagination({
            items: numItems,
            itemsOnPage: perPage,

    
            // This is the actual page changing functionality.
            onPageClick: function(pageNumber) {
                // We need to show and hide `tr`s appropriately.
                var showFrom = perPage * (pageNumber - 1);
                var showTo = showFrom + perPage;
    
                // We'll first hide everything...
                items.hide()
                     // ... and then only show the appropriate rows.
                     .slice(showFrom, showTo).show();

                jQuery([document.documentElement, document.body]).animate({
                    scrollTop: jQuery(".podcast-topics-posts-outer").offset().top - 100
                }, 1000);
            }
        });
    }
  };

jQuery(document).ready(function() {
    paginatePodcastBlocks('all-topics');

    jQuery('.podcast-topic-filter-link').on('click', function(e){
        e.preventDefault();
        var link = jQuery(this);
        jQuery('.podcast-topic-filter-link').removeClass('active');
        link.addClass('active');
        var slug = link.data('topic-slug');
        
        paginatePodcastBlocks( slug );
    });
    
});