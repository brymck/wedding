var DAY_COUNT = "#day_count";
var WEDDING_DATE = new Date(2013, 4, 27);

// Floating the navigation bar
function stickyNav() {
  var $nav = $("nav");
  var minVert = $nav.parent().offset().top;
  var isFixed = false;
  var $win = $(window);

  $win.scroll(function() {
    var scrollTop = $win.scrollTop();
    var shouldBeFixed = scrollTop > minVert;
    if (shouldBeFixed) {
      if (!isFixed) {
        $nav.css({
          position: "fixed",
          top: 0,
          left: $nav.offset().left,
          width: $nav.width()
        });
        isFixed = true;
      }
    } else {
      if (isFixed) {
        $nav.css({
          position: "static",
        });
        isFixed = false;
      }
    }
  });
}

function partialsPath(href) {
  var parts = href.split("/");
  var last = parts.splice(-1);
  parts.push("partials");
  parts.push(last);
  return parts.join("/");
}

function pjaxNav() {
  // Test for browser support
  if (!(window.history && window.history.pushState)) {
    return false;
  }

  var $pjaxContainer = $("#pjax-container");
  var $nav_anchors = $("nav a");

  $nav_anchors.click(function(event) {
    var $a = $(event.currentTarget);
    var href = $a.attr("href");

    // Move currently highlighted link
    $nav_anchors.removeClass("current");
    $a.addClass("current");
    var title = $a.text();

    $.ajax({
      url: partialsPath(href)
    }).done(function(response) {
      document.title = title;
      history.pushState(null, title, href);
      $pjaxContainer.fadeOut("fast", function() {
        $pjaxContainer.html(response);
        $pjaxContainer.fadeIn();
      });
    }).fail(function(response) {
      console.debug(response);
    });
    event.preventDefault();
  });
}

function showDayCount(id, date) {
  var days_til = Math.round((date - new Date()) / 86400000, 0);
  var days_plural = (Math.abs(days_til) === 1 ? "day" : "days");
  var til_since = (days_til < 0 ? "since" : "til");
  var text = " - " + days_til + " " + days_plural + " " + til_since + " our wedding";

  $(id).text(text);
}

$(function() {
  showDayCount(DAY_COUNT, WEDDING_DATE);
  stickyNav();
  pjaxNav();
});
