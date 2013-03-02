var DAY_COUNT = "#day_count";
var WEDDING_DATE = new Date(2013, 3, 27);
var remembered = {
  number:  1,
  wedding: true,
  friday:  true
};

// Floating the navigation bar
function stickyNav() {
  var $nav = $("nav");
  var $parent = $nav.parent();
  var minVert = $parent.offset().top + parseInt($parent.css("padding-top"));
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

function checkAttendance() {
  var attending = document.getElementById("attending");
  if (attending) {
    var status  = attending.checked;
    var number  = document.getElementById("number");
    var wedding = document.getElementById("wedding");
    var friday  = document.getElementById("friday");

    if (status) {
      number.value    = remembered.number;
      wedding.checked = remembered.wedding;
      friday.checked  = remembered.friday;
    } else {
      remembered.number  = number.value;
      remembered.wedding = wedding.checked;
      remembered.friday  = friday.checked;
      number.value = 0;
      wedding.checked = false;
      friday.checked = false;
    }
    number.disabled  = !status;
    wedding.disabled = !status;
    friday.disabled  = !status;
  }
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
    var title = $a.data("title") || $a.text();

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
