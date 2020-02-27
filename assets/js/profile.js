(function($) {
  "use strict";

  $(document).ready(function() {
    bsCustomFileInput.init();
  });

  $(document).scroll(function() {
    var scrollDistance = $(this).scrollTop();
    if (scrollDistance > 100) {
      $(".scroll-to-top").fadeIn();
    } else {
      $(".scroll-to-top").fadeOut();
    }
  });

  var navbarCollapse = function() {
    $("#mainNav").addClass("navbar-shrink");
  };

  navbarCollapse();
})(jQuery);
