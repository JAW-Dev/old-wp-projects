jQuery(document).ready(function() {

    var tweet_max = 124;
    var tweet_href = "https://twitter.com/intent/tweet?text=@MichaelKitces";

    update_tweet_count(tweet_max);

    function update_tweet_count(value) {
        jQuery('span.tweet-count').text(value);
    }

    function update_tweet_text(text) {
        new_url = tweet_href + " " + text;
        jQuery('#tweet-button').attr('href', new_url);
    }

    jQuery('#tweet').keyup(function() {
        var current_text = jQuery('#tweet').val();
        var text_length = current_text.length;
        var characters_left = tweet_max - text_length;
        update_tweet_count(characters_left);
        update_tweet_text(current_text);
    });

});
