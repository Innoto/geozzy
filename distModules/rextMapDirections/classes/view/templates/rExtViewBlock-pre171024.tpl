<!-- rExtViewBlock.tpl en rExtMapDirections module -->
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
</style>

<div class="rExtMapDirections">
  <div class="mapRoute">
    <div class="container resDirContainer">
      <p>{t}Type an address or mark on the map the place of departure{/t}:</p>
      <form class="mapRouteForm jsMapDirectionsForm">
        <div class="row">
          <div class="col-md-4">
            <input name="mapRouteOrigin">
          </div>
          <div class="col-md-4">
            <button type="submit">{t}Get directions{/t}</button>
          </div>
        </div>
      </form>
      <div class="routeMode jsMapDirectionsMode">
        <div data-route-mode="0" class="routeModeButton active"><i data-route-mode="0" class="fas fa-car fa-fw"></i></div>
        <div data-route-mode="1" class="routeModeButton"><i data-route-mode="1" class="fas fa-male fa-fw"></i></div>
        <div data-route-mode="2" class="routeModeButton"><i data-route-mode="2" class="fas fa-bus fa-fw"></i></div>
        <span class="routeInfo jsMapDirectionsInfo">{t}Route information{/t}</span>
      </div>
      <div class="tabList jsMapDirectionsShow">{t}Show route description{/t} <i class="fas fa-sort-down"></i><i class="fas fa-sort-up" style="display:none;"></i></div>
      <div id="comollegarListado" class="jsMapDirectionsList"></div>
    </div>
  </div>
</div>

<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtMapDirectionsData = {
    title: '{$rExt.data.title|escape:'javascript'}',
    lat: {$rExt.data.locLat},
    lng: {$rExt.data.locLon},
    zoom: {$rExt.data.defaultZoom},
    wrapperMap: '.resMapContainer',
    wrapperRoute: '.rExtMapDirections .mapRoute',
    scrollTopMargin: 130
  };
</script>
<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
