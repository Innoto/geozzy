<!DOCTYPE html>
<!-- portada.tpl en app de Geozzy -->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>galiciaagochada</title>

  {$css_includes}
  {$js_includes}
  <script src="https://maps.googleapis.com/maps/api/js">  </script>

</head>
<body>

  <div class="explorerLayout {$explorerType} mapFull clearfix">
    <!--duContainer -->
    <div class="explorerContainer explorer-container-du"></div>
    <!--filterContainer -->
    <div class="explorerContainer explorer-container-filter"></div>
    <!--mapContainer -->
    <div class="explorerContainer explorer-container-map">  <div id="explorerMap"></div> </div>
    <!--galleryContainer -->
    <div class="explorerContainer explorer-container-gallery"></div>
  </div>

  <script>
  $( document ).ready(function() {
      // gmaps init
      var mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        zoom: 7
      };
      resourceMap = new google.maps.Map( document.getElementById('explorerMap'), mapOptions);
  });
  </script>
</body>
</html>
<!-- /portada.tpl en app de Geozzy -->
