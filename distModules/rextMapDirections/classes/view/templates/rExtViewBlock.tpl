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
        <span class="routeInfo">{t}Route information{/t}</span>
      </div>
      <div class="tabList">{t}Show route description{/t} <i class="fa fa-sort-down"></i><i class="fa fa-sort-up" style="display:none;"></i></div>
      <div id="comollegarListado"></div>
    </div>
  </div>
  <div class="resMapWrapper">
    <!--div class="resMapContainer" style="">

    </div-->
  </div>
</div>


<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>-->
<script type="text/javascript">
var geozzy = geozzy || {};



geozzy.rExtMapDirectionsData = {
  title: '{$rExt.data.title}',
  lat: {$rExt.data.locLat},
  lng: {$rExt.data.locLon},
  zoom: {$rExt.data.defaultZoom},
  wrapper: '.rExtMapDirections .resMapContainer',
  wrapperRoute: '.rExtMapDirections .mapRoute',
  scrollTopMargin: 130
};


/*
$(document).ready( function() {
  setTimeout(function(){


    if( typeof geozzy.rExtMapDirectionsData.wrapperRoute !== 'undefined' ) {
      geozzy.rExtMapDirectionsController.prepareRoutes( geozzy.rExtMapDirectionsData );
    }

  },300);

});
*/
</script>

<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
