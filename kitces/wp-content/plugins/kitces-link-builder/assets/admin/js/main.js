function mkCopyToClipboard(str) {
  function listener(e) {
    e.clipboardData.setData("text/html", str);
    e.clipboardData.setData("text/plain", str);
    e.preventDefault();
  }
  document.addEventListener("copy", listener);
  document.execCommand("copy");
  document.removeEventListener("copy", listener);

  return true;
}

jQuery(document).ready(function () {
  jQuery("#kitces-link-builder-form-submit").on("click", function (event) {
    event.preventDefault();

    // Elements
    let button = jQuery(this);
    let errorDiv = jQuery("#kitces-link-builder-error");
    let hrefEl = jQuery("#kitces_link_builder_url");
    let linkTextEl = jQuery("#kitces_link_builder_link_text");
    let titleEl = jQuery("#kitces_link_builder_title");
    let categoryEl = jQuery("#kitces_link_builder_link_category");
    let newTabEl = jQuery("#kitces_link_builder_new_tab");
    let noFollowEl = jQuery("#kitces_link_builder_nofollow");
    let sponsoredEl = jQuery("#kitces_link_builder_sponsored");
    let ugcEl = jQuery("#kitces_link_builder_ugc");

    // Values
    let href = hrefEl.val();
    let linkText = linkTextEl.val();
    let title = titleEl.val();
    var category = null;
    if (categoryEl.length) {
      category = categoryEl.val();
    }
    let newTab = newTabEl.is(":checked");
    let noFollow = noFollowEl.is(":checked");
    let sponsored = sponsoredEl.is(":checked");
    let ugc = ugcEl.is(":checked");

    if (linkText.length && href.length) {
      errorDiv.html("");

      var rel = "";
      if (noFollow) {
        rel += "nofollow";
      }

      if (sponsored) {
        rel += " sponsored";
      }

      if (ugc) {
        rel += " ugc";
      }

      var html = '<a href="' + href + '"';

      if (title) {
        title = title
          .replace(/</g, "&lt;")
          .replace(/>/g, "&gt;")
          .replace(/"/g, "&quot;");
        html += ' title="' + title + '"';
      }

      if (newTab) {
        if (rel) {
          html += ' rel="noopener ' + rel + '" target="_blank"';
        } else {
          html += ' rel="noopener" target="_blank"';
        }
      } else {
        if (rel) {
          html += ' rel="' + rel + '"';
        }
      }

      if (categoryEl.length) {
        html += ' data-wknd-cat="' + category + '"';
      }

      html += ">";
      html += linkText;
      html += "</a>";

      mkCopyToClipboard(html);
      button.html("Copied!");

      setTimeout(function () {
        button.html("Generate & Copy Link");
        hrefEl.val("");
        linkTextEl.val("");
        titleEl.val("");
        categoryEl.prop("selectedIndex", 0);
        newTabEl.prop("checked", true);
        noFollowEl.prop("checked", false);
        sponsoredEl.prop("checked", false);
        ugcEl.prop("checked", false);
      }, 2500);
    } else {
      errorDiv.html("A URL and Link Text are required.");
    }

    // Get all values
    // Generate link
    // Copy link to clipboard
    // Show "copied" message
    // Clear out all inputs
  });
});
