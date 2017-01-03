var setCustomerHeight = function() {
  var element = $('main #customers .customer_logo');
  element.css('height', element.css('width'));
}

var setFullScreenHeight = function(element) {
  var windowHeight = $(window).height();
  $(element).css('height', String(windowHeight) + "px");
}

var setServicesHeight = function() {
  var headingHeight = $('#services .heading').height() + 100 + 40; // paddings
  var imageHeight = $(window).height() - headingHeight;
  $('#services .item img').css('height', String(imageHeight) + "px");
}

var cutServiceDescriptions = function() {
  $('main #services .service_description').each(function() {
    var text = $(this).text();
    $(this).text(text.substring(0, 300) + "...");
  })
}

$(document).ready(function() {
  $(window).scroll(function() {
    if($(this).scrollTop() > 50) {
      $('header nav').addClass('reduced');
    } else {
      $('header nav').removeClass('reduced');
    }
  });

  setFullScreenHeight('#jumbotron');
  setServicesHeight();
  setCustomerHeight();
  cutServiceDescriptions();
});
