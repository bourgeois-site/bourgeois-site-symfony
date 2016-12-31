var setHeight = function() {
  var element = $('main #customers .customer_logo');
  element.css('height', element.css('width'));
}

var setHeightJumbotron = function() {
  var windowHeight = $(window).height();
  $('main #jumbotron').css('height', String(windowHeight) + "px");
}

var cutServiceDescriptions = function() {
  $('main #services .service_description').each(function() {
    var text = $(this).text();
    $(this).text(text.substring(0, 300) + "...");
  })
}

$(document).ready(function() {
  $('main #services .service_description').slideUp(300);
  cutServiceDescriptions();
  setHeightJumbotron();
  setHeight();
  $(window).scroll(function() {
    if($(this).scrollTop() > 50) {
      $('header nav').addClass('reduced');
    } else {
      $('header nav').removeClass('reduced');
    }
  });

  $('main #services .service').mouseenter(function() {
    var caption = $(this).find('.service_caption');
    caption.css('color', '#000');
    caption.css('text-shadow', 'none');
    caption.children('.service_description').slideDown(300);
    var img = $(this).find('img');
    img.css('opacity', '0.1');
  });
  $('main #services .service').mouseleave(function() {
    var caption = $(this).find('.service_caption');
    caption.css('color', '#fff');
    caption.css('text-shadow', '0 1px 1px #000');
    caption.children('.service_description').slideUp(300);
    var img = $(this).find('img');
    img.css('opacity', '1.0');
  });

  $('a[data-target="#contacts"]').on('click', function() {
    if ($('#contacts').find('.ymaps-2-1-47-map').length == 0) {
      ymaps.ready(function() {
        var dzerCoord = [56.237328, 43.448866];
        var nnCoord = [56.269391, 43.888957];
        var zoomLevel = 17;
        var hint = "Студия \"Буржуа\"";
        var baloon = "<h4>Студия \"Буржуа\"</h4><p>Ремонт квартир и ванных комнат, отделочные работы</p>";

        var dzerMap = new ymaps.Map("dzer_map", {
          center: dzerCoord,
          zoom: zoomLevel
        });
        var dzerPlacemark = new ymaps.Placemark(dzerCoord, {
          hintContent: hint,
          balloonContent: baloon
        }, {
          preset: 'islands#redGlyphIcon',
        });
        dzerMap.geoObjects.add(dzerPlacemark);
        var nnMap = new ymaps.Map("nn_map", {
          center: nnCoord,
          zoom: zoomLevel
        });
        var nnPlacemark = new ymaps.Placemark(nnCoord, {
          hintContent: hint,
          balloonContent: baloon
        }, {
          preset: 'islands#redGlyphIcon',
        });
        nnMap.geoObjects.add(nnPlacemark);
      });
    }
  });
});
