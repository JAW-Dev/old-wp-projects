jQuery(document).ready(function () {
  jQuery(document).on(
    "click",
    ".favorite-wrap button.favorite-toggle-trigger",
    function (e) {
      e.preventDefault();
      var postID = jQuery(this).data("post-id");
      var favoriteWrap = jQuery(this).parents(".favorite-wrap");
      var buttonText = favoriteWrap.find("span.button-text");
      jQuery.ajax({
        url: favorite_data.ajax_url,
        type: "post",
        data: {
          action: "favorite_callback",
          dataType: "json",
          postID: postID,
        },
        success: function (response) {
          var data = JSON.parse(response);
          if (data.status == 1) {
            favoriteWrap.addClass("is-favorited");
            buttonText.text("Remove from Favorites");
          } else if (data.status == 0) {
            favoriteWrap.removeClass("is-favorited");
            buttonText.text("Add to Favorites");
          }
        },
      });
    }
  );
});
