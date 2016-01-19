<!-- rExtViewBlock.tpl en rExtMapDirections module -->

<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language={$GLOBAL_LANG_AVAILABLE[$GLOBAL_C_LANG].i18n}"></script>-->
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
  }
  .rExtMapDirections .resDirContainer .tabList {
    display: none;
  }
  .rExtMapDirections .resDirContainer #comollegarListado {
    display: none;
    height: 250px;
    overflow: auto;
  }
  .rExtMapDirections .resDirContainer #comollegarListado .adp-directions {
    width: 100%;
  }
  .rExtMapDirections .resDirContainer .routeMode {
    display: none;
  }
  .rExtMapDirections .resDirContainer .routeModeButton {
    display: inline-block;
    padding: 1px 2px;
    border: 2px solid #444;
    border-radius: 0.2em;
    font-size: 18px;
    color: #444;
    cursor: pointer;
  }
  .rExtMapDirections .resDirContainer .routeModeButton.active {
    color: #44F;
    border-color: #44F;
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
      <div class="routeMode">
        <div data-route-mode="0" class="routeModeButton active"><i data-route-mode="0" class="fa fa-car fa-fw"></i></div>
        <div data-route-mode="1" class="routeModeButton"><i data-route-mode="1" class="fa fa-male fa-fw"></i></div>
        <div data-route-mode="2" class="routeModeButton"><i data-route-mode="2" class="fa fa-bus fa-fw"></i></div>
        <!-- img data-route-mode="1" class="routeModeButton" src="/media/module/rextMapDirections/img/route-mode-1.png" -->
        <span class="routeInfo">Route info</span>
      </div>
      <div class="tabList">Mostrar la descripción de la ruta</div>
      <div id="comollegarListado"></div>
    </div>
  </div>
  <div class="resMapWrapper">
    <div class="resMapContainer" style="">
      <!-- google.map -->
    </div>
  </div>
</div>

<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
