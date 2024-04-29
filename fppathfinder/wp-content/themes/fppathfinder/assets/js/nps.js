jQuery(document).ready(function () {
  let nps_data = data.nps_data;
  let showedCookie = getCookie("nps-showed");
  let nps_holder = jQuery(".nps-holder");

  if (!nps_data.disable_nps && nps_data.nps_form_page != data.post_id) {
    //   No need to run the entire thing if we have the showed cookie
    if (0 === showedCookie.length || nps_data.nps_show_admin) {
      if (nps_holder !== undefined) {
        setTimeout(() => {
          maybe_display_nps();
        }, parseInt(nps_data.nps_time_till_run));
      }
    } else {
      console.log("no nps");
    }
  }

  function maybe_display_nps() {
    jQuery.ajax({
      type: "GET",
      url: data.ajax_url,
      data: {
        action: "nps_ajax_callback",
      },
      success: function (response) {
        if (response !== undefined) {
          nps_holder.append(response);
        }
        setCookie("nps-showed", 1, 2);
      },
    });
  }

  jQuery(".nps-holder").on("click", ".close-nps, .survey-links a", function () {
    setCookie("nps-closed", 1, nps_data.nps_skip_days_closed);
    jQuery(this).parents("section.nps-outer").fadeOut();
  });

  jQuery(".nps-page .gform_wrapper form").on("submit", function () {
    setCookie("nps-filled-out", 1, nps_data.nps_skip_days_filled);
  });
});
