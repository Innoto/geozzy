<!DOCTYPE html>
<!-- portada.tpl en app de Geozzy -->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>galiciaagochada</title>

{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key)}{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}{/if}
  <script src="{$gMaps}&libraries=places"></script>
  {$client_includes}

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
