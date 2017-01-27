var setFullScreenHeight = function(element) {
  var windowHeight = $(window).height();
  $(element).css('height', String(windowHeight) + "px");
}

var cutServiceDescriptions = function() {
  $('main #services .service_description .section_description').each(function() {
    var text = $(this).text();
    $(this).text(text.substring(0, 200) + "...");
  })
}

var resizeServices = function() {
  var paddingContainer = 15;
  var width = $(window).width();
  if(width < 600) {
    newWidth = width - 2 * paddingContainer;
  } else if(width < 768) {
    newWidth = width - 200 - 2 * paddingContainer;
  } else if(width < 992) {
    newWidth = width / 2 - 2 * paddingContainer;
  } else if(width < 1200) {
    newWidth = width / 3 - 2 * paddingContainer;
  } else {
    newWidth = width / 4 - 2 * paddingContainer;
  }
  $('.service').css('width', newWidth);
  $('.service').css('height', newWidth);
};

$(document).ready(function() {
  $('[title]').tooltip({ placement: 'top'});

  resizeServices();

  var spaceBetweenServices = 24;

  $('#services .container').masonry({
    itemSelector: '.service',
    gutter: spaceBetweenServices,
    isFitWidth: true
  });

  $(window).scroll(function() {
    if($(this).scrollTop() > 50) {
      $('header nav').addClass('reduced');
    } else {
      $('header nav').removeClass('reduced');
    }
  });

  setFullScreenHeight('#jumbotron');
  cutServiceDescriptions();

  $('#services .service_description').slideUp(300);

  $('#services .service').mouseenter(function() {
    var caption = $(this).find('.service_caption');
    caption.children('.service_description').slideDown(300);
    var img = $(this).find('img');
    img.css('opacity', '0.1');
  });

  $('#services .service').mouseleave(function() {
    var caption = $(this).find('.service_caption');
    caption.children('.service_description').slideUp(300);
    var img = $(this).find('img');
    img.css('opacity', '1.0');
  });

  $('#call_form textarea').on('click', function() {
    $(this).css('height', '200px');
  });
});

$(window).resize(function() {
  resizeServices();
});
