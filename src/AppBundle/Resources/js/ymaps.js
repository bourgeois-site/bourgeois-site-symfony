$(document).ready(function() {
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

