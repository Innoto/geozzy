<!DOCTYPE html>
<!-- portada.tpl en app de Geozzy -->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>galiciaagochada</title>

  {$client_includes}

{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key) && $cogumelo.publicConf.google_maps_key}
{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}
{/if}
  <script src="{$gMaps}&libraries=places"></script>

</head>
<body>
  <div style=" padding:20px; font-size:18px; ">
    <div class="container">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc et condimentum ex, quis vestibulum dui. Aenean ut ornare magna. Ut est ante, pellentesque in faucibus eget, dignissim ac velit. Cras eget ipsum dolor. Praesent ultrices nec nibh et lacinia. Cras malesuada, libero ut ullamcorper luctus, urna lectus accumsan dolor, eget interdum velit eros fermentum tortor. Nullam est ligula, auctor ut mollis quis, ornare congue tortor. Fusce rhoncus ligula eget quam rhoncus, interdum suscipit tellus finibus. Phasellus interdum, elit accumsan semper iaculis, tellus ex cursus urna, quis fringilla tortor mi accumsan velit.</p>
    </div>
  </div>
  <div id="explorerSectionExample" class="explorerLayout {$explorerType} mapFull clearfix">
    <!--duContainer -->
    <div class="explorerContainer explorer-container-du"></div>
    <!--filterContainer -->
    <div class="explorerContainer explorer-container-filter"></div>
    <!--mapContainer -->
    <div class="explorerContainer explorer-container-map"> <div id="explorerMap"></div> </div>
    <!--galleryContainer -->
    <div class="explorerContainer explorer-container-gallery"></div>
  </div>
  <div style=" padding:20px; font-size:20px;">
    <div class="container">
      <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc et condimentum ex, quis vestibulum dui. Aenean ut ornare magna. Ut est ante, pellentesque in faucibus eget, dignissim ac velit. Cras eget ipsum dolor. Praesent ultrices nec nibh et lacinia. Cras malesuada, libero ut ullamcorper luctus, urna lectus accumsan dolor, eget interdum velit eros fermentum tortor. Nullam est ligula, auctor ut mollis quis, ornare congue tortor. Fusce rhoncus ligula eget quam rhoncus, interdum suscipit tellus finibus. Phasellus interdum, elit accumsan semper iaculis, tellus ex cursus urna, quis fringilla tortor mi accumsan velit.</p>
      <img src="http://lorempixel.com/300/160" />
    </div>
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
