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
      <p>{t}Type an address or mark on the map the place of departure{/t}:</p>
      <form class="mapRouteForm">
        <div class="row">
          <div class="col-md-4">
            <input name="mapRouteOrigin">
          </div>
          <div class="col-md-4">
            <button type="submit">{t}Get directions{/t}</button>
          </div>
        </div>
      </form>
      <div class="routeMode">
        <div data-route-mode="0" class="routeModeButton active"><i data-route-mode="0" class="fa fa-car fa-fw"></i></div>
        <div data-route-mode="1" class="routeModeButton"><i data-route-mode="1" class="fa fa-male fa-fw"></i></div>
        <div data-route-mode="2" class="routeModeButton"><i data-route-mode="2" class="fa fa-bus fa-fw"></i></div>
        <!-- img data-route-mode="1" class="routeModeButton" src="/media/module/rextMapDirections/img/route-mode-1.png" -->
        <span class="routeInfo">{t}Route information{/t}</span>
      </div>
      <div class="tabList">{t}Show route description{/t} <i class="fa fa-sort-down"></i><i class="fa fa-sort-up" style="display:none;"></i></div>
      <div id="comollegarListado"></div>
    </div>
  </div>
  <div class="resMapWrapper">
    <div class="resMapContainer" style="">
      <!-- google.map -->
    </div>
  </div>
</div>


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

$(document).ready( function() {
  if( typeof geozzy.rExtMapDirectionsData !== 'undefined' ) {
    geozzy.rExtMapDirectionsController.prepareMap( geozzy.rExtMapDirectionsData );
  }


  if( typeof geozzy.rExtMapDirectionsData.wrapperRoute !== 'undefined' ) {
    geozzy.rExtMapDirectionsController.prepareRoutes( geozzy.rExtMapDirectionsData );
  }
});

</script>

<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
