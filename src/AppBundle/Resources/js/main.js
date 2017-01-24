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

$(document).ready(function() {
  $('[title]').tooltip({ placement: 'top'});

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
