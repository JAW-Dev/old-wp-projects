function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + exdays * 24 * 60 * 60 * 1000);
  var expires = "expires=" + d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var ca = document.cookie.split(";");
  for (var i = 0; i < ca.length; i++) {
    var c = ca[i];
    while (c.charAt(0) == " ") {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

// // Typekit
try { Typekit.load({ async: true }); } catch (e) { }

var stylesheetUrl = data.stylesheetUrl;

var MobileMenu = {
    config: {},
    toggleNav: function() {
        var actualPage = MobileMenu.config.toggles.actualPage;
        var mobileMenu = MobileMenu.config.toggles.mobileMenu;
        var header = MobileMenu.config.toggles.header;
        var body = MobileMenu.config.toggles.body;

        actualPage.el.toggleClass(actualPage.classToggle);
        mobileMenu.el.toggleClass(mobileMenu.classToggle);
        body.el.toggleClass(body.classToggle);

        // If it's a fixed header, move it left as well. Otherwise, let the actual page move everything
        if(MobileMenu.config.flags.fixedHeader) {
            header.el.toggleClass(header.classToggle);
        }
    },
    toggleSubNav: function(parent) {
        parent.toggleClass("active");
        parent.children(".sub-menu").slideToggle(500);
    },
    slideToggleNav: function() {
        MobileMenu.config.targets.mobileNavContainer.slideToggle(300);
    },
    setUpIcons: function() {
        var ajax = new XMLHttpRequest();
        var closeIconContainer = MobileMenu.config.targets.mobileMenuCloseIconContainer;

        ajax.open("GET", stylesheetUrl + "/assets/icons/src/close.svg", true);
        ajax.send();
        ajax.onload = function(e) {
            closeIconContainer.html(ajax.responseText);
        }
    },
    init: function(template_url, configObj) {
        configObj = configObj || { toggles: {}, targets: {}, flags: {} };
        configObj.toggles.actualPage = configObj.toggles.actualPage || { el: jQuery(".site-container"), classToggle: 'move-left' };
        configObj.toggles.header = configObj.toggles.header || { el: jQuery("header"), classToggle: 'move-left' };
        configObj.toggles.mobileMenu = configObj.toggles.mobileMenu || { el: jQuery(".mobile-menu"), classToggle: 'move-in' };
        configObj.toggles.body = configObj.toggles.body || { el: jQuery("body"), classToggle: 'locked' };

        configObj.targets.mobileNavigation = configObj.targets.mobileNavigation || jQuery(".mobile-navigation");
        configObj.targets.mobileNavContainer = configObj.targets.mobileNavContainer || jQuery(".mobile-navigation-container");
        configObj.targets.mobileMenuIcon = configObj.targets.mobileMenuIcon || jQuery(".mobile-menu-icon");
        configObj.targets.mobileMenuCloseIconContainer = configObj.targets.mobileMenuCloseIconContainer || configObj.toggles.mobileMenu.el.find(".icon-close-container");

        configObj.flags.fixedHeader = configObj.flags.fixedHeader || false;
        configObj.templateUrl = configObj.templateUrl || template_url || "";

        MobileMenu.config = configObj;

        MobileMenu.config.targets.mobileMenuIcon.on("click", MobileMenu.toggleNav);
        MobileMenu.config.targets.mobileMenuIcon.on("click", MobileMenu.slideToggleNav);
        MobileMenu.config.targets.mobileMenuCloseIconContainer.on("click", MobileMenu.toggleNav);
        jQuery(".mobile-menu .menu").children("li.menu-item-has-children").on("click", function(){
            MobileMenu.toggleSubNav(jQuery(this));
        });

        MobileMenu.setUpIcons();
    }
};

jQuery.expr[":"].icontains = function (a, i, m) {
  return jQuery(a).text().toUpperCase().indexOf(m[3].toUpperCase()) >= 0;
};

jQuery(document).ready(function () {
  // Initialize Mobile Menu
  MobileMenu.init();

  // Initialize accessible menus
  jQuery(document).gamajoAccessibleMenu();

  // Run the svg for everyone
  svg4everybody({
    polyfill: true,
  });

  // Accordion
  jQuery(".accordion-row-header").on("click", function () {
    jQuery(".accordion-row-content").slideUp(300);
    if (jQuery(this).hasClass("active-ar")) {
      jQuery(this).removeClass("active-ar");
    } else {
      jQuery(".accordion-row-header").removeClass("active-ar");
      jQuery(this).next(".accordion-row-content").slideDown(300);
      jQuery(this).addClass("active-ar");
      return false;
    }
  });

  // Check to see if menu goes outside of container
  jQuery(".genesis-nav-menu .menu-item").on(
    "mouseenter mouseleave",
    function (e) {
      if (jQuery(this).find(".sub-menu").length) {
        var elm = jQuery(this).children(".sub-menu");
        var off = elm.offset();
        var l = off.left;
        var w = elm.width();
        var docH = jQuery("body").height();
        var docW = jQuery("body").width();

        var isEntirelyVisible = l + w <= docW;

        if (!isEntirelyVisible) {
          jQuery(this).children(".sub-menu").addClass("edge");
        } else {
          jQuery(this).children(".sub-menu").removeClass("edge");
        }
      }
    }
  );

  // Simply Smooth scrolling. Requires two things.
  // 1. Use an a tag with an href contianing the id of an element so <a href="#target-div">Click to go to Target</a>
  // 2. Have a div/element with the id of you use in the href above
  // This was adapted from here - https://www.abeautifulsite.net/smoothly-scroll-to-an-element-without-a-jquery-plugin-2

  jQuery('a[href^="#"]').on("click", function (event) {
    var target = jQuery(this.getAttribute("href"));
    if (target.length) {
      event.preventDefault();
      jQuery("html, body").stop().animate(
        {
          scrollTop: target.offset().top,
        },
        1000
      );
    }
  });

  jQuery('.tml input[name="log"]').attr("placeholder", "Your Username");
  jQuery('.tml input[name="pwd"]').attr("placeholder", "Your Password");

  jQuery(".modaal-img, .button.fifty-image-content-image__pop-link a").modaal({
    type: "image",
  });

  jQuery(".modaal-video").modaal({
    type: "video",
  });

  jQuery(".upsell-modal").modaal({
    width: 800,
  });

  jQuery("a.subscription-cancel-link").modaal({
    width: 650,
  });

  let downgradeOptionContent = jQuery(
    ".inner-cancel-content.has-downgrade-option"
  );
  if (downgradeOptionContent !== undefined) {
    let cancelFormWrap = downgradeOptionContent.find(".cancel-form-wrap");
    let downgradeContent = downgradeOptionContent.find(".downgrade-option");

    cancelFormWrap.hide();

    jQuery(
      ".inner-cancel-content.has-downgrade-option .continue-cancel.button a"
    ).on("click", function (e) {
      e.preventDefault();
      downgradeContent.hide();
      cancelFormWrap.fadeIn();
    });
  }

  jQuery(".inner-cancel-content").on(
    "click",
    ".submit-cancel-button.button a",
    function (e) {
      let theForm = jQuery(".cancel-form-wrap form");
      e.preventDefault();
      theForm.trigger("submit");
    }
  );

  // Download filter packages.
  jQuery(".downloads-filter-bar .filter-link").on("click", function () {
    var filter = jQuery(this);
    var theClass = filter.attr("data-filter-class");

    if (!filter.hasClass("current")) {
      jQuery(".downloads-filter-bar .filter-link").removeClass("current");
      filter.addClass("current");

      jQuery(".download-archive-block").show();
      jQuery(".download-archive-block:not(." + theClass + ")").hide();
    }
  });

  // Download filter search.
  jQuery(".download-filter-input").on("change keyup", function () {
    var filterTerm = jQuery(this).val();
    if (filterTerm) {
      jQuery(".download-loop-inner-wrap")
        .find(".download-archive-block:not(:icontains(" + filterTerm + "))")
        .slideUp();
      jQuery(".download-loop-inner-wrap")
        .find(".download-archive-block:icontains(" + filterTerm + ")")
        .slideDown();
    } else {
      jQuery(".download-archive-block").slideDown();
    }
    return false;
  });

  jQuery.each(jQuery('a[href$="/login/"]'), function (key, value) {
    var currentPage = window.location.href;

    if (currentPage.length) {
      jQuery(this).attr("href", "/login/?redirect=" + currentPage);
    }
  });

  jQuery(".testimonial-list-slider").each(function () {
    var slickTestimonialList = jQuery(this);
    var outerSldier = slickTestimonialList.parents(
      ".testimonial-list-slider-outer"
    );

    slickTestimonialList.slick({
      adaptiveHeight: false,
      infinite: true,
      slidesToScroll: 1,
      slidesToShow: 1,
      arrows: true,
      nextArrow: outerSldier.find(".right-arrow"),
      prevArrow: outerSldier.find(".left-arrow"),
      autoplay: true,
      autoplaySpeed: 10000,
      dots: true,
    });
  });

  jQuery(".footer-cta__testimonial-slider").each(function () {
    var slickAngledIndividual = jQuery(this);
    var outerSldier = slickAngledIndividual.parents(
      ".footer-cta__outer-testimonial-slider"
    );

    slickAngledIndividual.slick({
      adaptiveHeight: false,
      infinite: true,
      slidesToScroll: 1,
      slidesToShow: 2,
      arrows: true,
      nextArrow: outerSldier.find(".right-arrow"),
      prevArrow: outerSldier.find(".left-arrow"),
      autoplay: true,
      autoplaySpeed: 10000,
      dots: true,
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });
  });
});

function initSlickSliders() {
  jQuery(".resource-slick-slider")
    .not(".slick-initialized")
    .slick({
      draggable: false,
      slidesToShow: 4,
      slidesToScroll: 1,
      prevArrow:
        '<div class="back-arrow"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M14 20.9474L22.4737 29.4211C22.8947 29.8421 23.5263 29.8421 23.9474 29.4211C24.3684 29 24.3684 28.3684 23.9474 27.9474L16.2105 20.2105L24.3684 12.0526C24.7895 11.6316 24.7895 11 24.3684 10.5789C23.9474 10.1579 23.3158 10.1579 22.8947 10.5789L14 19.4737C13.5789 19.8947 13.5789 20.5263 14 20.9474ZM0 20C0 31.0526 8.94737 40 20 40C31.0526 40 40 31.0526 40 20C40 8.94737 31.0526 0 20 0C8.94737 0 0 8.94737 0 20ZM2.10526 20C2.10526 10.1053 10.1053 2.10526 20 2.10526C29.8947 2.10526 37.8947 10.1053 37.8947 20C37.8947 29.8947 29.8947 37.8947 20 37.8947C10.1053 37.8947 2.10526 29.8947 2.10526 20Z" fill="#293D52"/></svg></div>',
      nextArrow:
        '<div class="next-arrow"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M26 20.9474L17.5263 29.4211C17.1053 29.8421 16.4737 29.8421 16.0526 29.4211C15.6316 29 15.6316 28.3684 16.0526 27.9474L23.7895 20.2105L15.6316 12.0526C15.2105 11.6316 15.2105 11 15.6316 10.5789C16.0526 10.1579 16.6842 10.1579 17.1053 10.5789L26 19.4737C26.4211 19.8947 26.4211 20.5263 26 20.9474ZM40 20C40 31.0526 31.0526 40 20 40C8.94737 40 0 31.0526 0 20C0 8.94737 8.94737 0 20 0C31.0526 0 40 8.94737 40 20ZM37.8947 20C37.8947 10.1053 29.8947 2.10526 20 2.10526C10.1053 2.10526 2.10526 10.1053 2.10526 20C2.10526 29.8947 10.1053 37.8947 20 37.8947C29.8947 37.8947 37.8947 29.8947 37.8947 20Z" fill="#293D52"/></svg></div>',
      responsive: [
        {
          breakpoint: 1200,
          settings: {
            slidesToShow: 3,
          },
        },
        {
          breakpoint: 900,
          settings: {
            slidesToShow: 2,
          },
        },
        {
          breakpoint: 700,
          settings: {
            slidesToShow: 1,
          },
        },
      ],
    });
}

jQuery(document).ready(initSlickSliders);
jQuery(document).on("facetwp-loaded", initSlickSliders);

jQuery(document).ready(function () {
  jQuery(".tooltip-button").click(function (event) {
    jQuery("#" + this.dataset.tooltipKey).slideToggle();
  });
});

jQuery(document).ready(function () {
  jQuery(".gs-start").on("click", function (event) {
    event.preventDefault();
    let qsWrap = jQuery(this).parents(".questions-wrap");

    if (qsWrap.length) {
      let qSelects = qsWrap.find("select");
      let selectValues = [];
      let selectedCount = 0;

      if (qSelects.length) {
        qSelects.each(function () {
          let thisSelect = jQuery(this);
          let thisQID = thisSelect.data("question-id");
          let thisSelectVal = thisSelect.val();

          if (thisSelectVal) {
            selectValues[thisQID] = thisSelect.val();
            selectedCount += 1;
          }
        });

        if (selectedCount < 1) {
          jQuery(".questions-wrap .error").slideDown();
        } else {
          jQuery(".questions-wrap .error").slideUp();

          //   Process the answers
          jQuery(".page-banner__subtitle.questions").hide();
          jQuery(".page-banner__subtitle.answer").fadeIn();
          jQuery(".answers-wrap").fadeIn();

          jQuery(".questions-wrap").hide();

          let answers = jQuery(".answers-wrap > .answer");
          if (answers.length) {
            answers.each(function () {
              let thisAnswer = jQuery(this);
              let questionID = thisAnswer.data("question-id");
              let answerID = thisAnswer.data("answer-id");
              let questionMode = thisAnswer.data("question-mode");

              if (typeof selectValues[questionID] !== "undefined") {
                let selectedValue = selectValues[questionID];
                if (answerID === selectedValue) {
                  if ("all" === questionMode) {
                    thisAnswer.fadeIn();
                  } else if ("random" === questionMode) {
                    let subAnswers = thisAnswer.find("> div");

                    if (subAnswers.length > 0) {
                      let randomNum = Math.floor(
                        Math.random() * subAnswers.length + 1
                      );
                      let answerCount = 1;

                      subAnswers.each(function () {
                        let thisSubAnswer = jQuery(this);
                        if (answerCount === randomNum) {
                          thisSubAnswer.fadeIn();
                        } else {
                          thisSubAnswer.hide();
                        }
                        answerCount += 1;
                      });
                    }

                    thisAnswer.fadeIn();
                  }
                }
              }
            });
          }
        }
      }
    }
  });
});

jQuery(document).ready(function () {
  let radios = jQuery(".nps-score input[type='radio']");

  if (radios !== undefined) {
    radios.each(function () {
      radioInput = jQuery(this);
      if (radioInput.is(":checked")) {
        radioInput.parents("li").addClass("selected-radio");
      }
    });
  }

  jQuery(".nps-score input[type='radio']").on("click", function () {
    let radio = jQuery(this);
    let radioWrap = radio.parents("ul.gfield_radio");

    radioWrap.find("li").removeClass("selected-radio");
    radio.parents("li").addClass("selected-radio");
  });
});

jQuery(document).ready(function () {
  const wasReferred = jQuery('#was-referred');
  const referredBy = jQuery('#reffered-by');

  jQuery(wasReferred).on('change', () => {
    if (wasReferred.prop("checked") == true) {
      referredBy.css('display', 'block');
    } else {
      referredBy.css('display', 'none');
    }
  })
});

jQuery(document).ready(function () {
  const checkboxs = jQuery('.question__checkbox');
  checkboxs.each(function (index, value) {
    jQuery(value).on('change', function () {
      const parent = jQuery(value).parent();
      const text = jQuery(parent).siblings('.text');
      const tooltip = jQuery(parent).siblings('.tooltip-button');
      const notes = jQuery(parent).siblings('.notes-button');
      const inputs = jQuery(parent).siblings('.inputs');

      jQuery(text).toggleClass('hidden-question');
      jQuery(tooltip).toggleClass('hidden-question');
      jQuery(notes).toggleClass('hidden-question');
      jQuery(inputs).toggleClass('hidden-question');
    })
  })
});

jQuery(document).ready(function () {
  const searchParams = new URLSearchParams(window.location.search);
  const partnership = searchParams.get('partnership');

  if (partnership) {
    const priceBlockButtons = jQuery('.price-block .button a');

    priceBlockButtons.each(function () {
      const link = jQuery(this).attr('href');
      jQuery(this).attr('href', link + '?partnership=' + partnership);
    })
  }
});

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
