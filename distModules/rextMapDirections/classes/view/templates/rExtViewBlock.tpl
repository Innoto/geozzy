<!-- rExtViewBlock.tpl en rExtMapDirections module -->

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>
<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtMapDirectionsData = {
    title: '{$rExt.data.title}',
    lat: {$rExt.data.locLat},
    lon: {$rExt.data.locLon},
    zoom: {$rExt.data.defaultZoom},
    wrapper: '.rExtMapDirections .resMapContainer',
    wrapperRoute: '.rExtMapDirections .resDirContainer'
  };
</script>

<style type="text/css">
  .rExtMapDirections .resDirContainer {
    color:blue;
  }
  .rExtMapDirections .resDirContainer #comollegarListado {
    height:30px;
  }
  .rExtMapDirections .resMapWrapper {
    height:400px;
  }
  .rExtMapDirections .resMapContainer {
    width:100%;
    height:100%;
  }
</style>

<div class="rExtMapDirections">
  <div class="mapRoute">
    <div class="container resDirContainer">
      <p>Teclea una dirección o marca en el mapa el lugar de partida</p>
      <form class="mapRouteForm">
        <input name="mapRouteOrigin">
        <button type="submit">Calcular ruta</button>
      </form>
      <div id="comollegarListado"></div>
    </div>
  </div>
  <div class="container resMapWrapper">
    <div class="resMapContainer" style="">
      <!-- google.map -->
    </div>
  </div>
</div>

<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
