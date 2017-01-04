var setFullScreenHeight = function(element) {
  var windowHeight = $(window).height();
  $(element).css('height', String(windowHeight) + "px");
}

var setWorksHeight = function() {
  var headingHeight = $('#works .heading').height() + 80 + 30 + 50; // paddings + header
  var imageHeight = $(window).height() - headingHeight;
  $('#works .item img').css('height', String(imageHeight) + "px");
}

var cutServiceDescriptions = function() {
  $('main #services .service_description').each(function() {
    var text = $(this).text();
    $(this).text(text.substring(0, 300) + "...");
  })
}

var setServicesHeight = function() {
  var width = $(window).width();
  var headingHeight = $('#services .heading').height() + 80 + 30 + 50; // paddings + header
  var workHeight = 200; // default
  if (width > 767 && width < 992) {
    workHeight = ($(window).height() - headingHeight) / 3;
  } else if (width > 991) {
    workHeight = ($(window).height() - headingHeight) / 2;
  }
  $('#services .service').css('height', String(workHeight) + 'px');
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
  cutServiceDescriptions();
  setWorksHeight();

  $('#services .service_description').slideUp(300);

  $('#services .service').mouseenter(function() {
    var caption = $(this).find('.service_caption');
    caption.css('color', '#000');
    caption.css('text-shadow', 'none');
    caption.children('.service_description').slideDown(300);
    var img = $(this).find('img');
    img.css('opacity', '0.1');
  });

  $('#services .service').mouseleave(function() {
    var caption = $(this).find('.service_caption');
    caption.css('color', '#fff');
    caption.css('text-shadow', '0 1px 1px #000');
    caption.children('.service_description').slideUp(300);
    var img = $(this).find('img');
    img.css('opacity', '1.0');
  });
});
