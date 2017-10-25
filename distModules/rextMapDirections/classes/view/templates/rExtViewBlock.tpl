<!-- rExtViewBlock.tpl en rExtMapDirections module -->
<style type="text/css">

  .rExtMapDirections.tabList {
    display: none;
  }

  .rExtMapDirections.wrapperInMap {
    padding: 10px;
    background-color: white;
  }

  .rExtMapDirections .mapDirInMapClose,
  .rExtMapDirections .mapDirInMapPrint {
    cursor: pointer;
    text-align: right;
  }

  .rExtMapDirections.routeMode {
    padding: 10px;
  }
  .rExtMapDirections.routeMode .routeModeButton {
    display: inline-block;
    padding: 1px 2px;
    border: 2px solid #444;
    border-radius: 0.2em;
    font-size: 18px;
    color: #444;
    cursor: pointer;
  }
  .rExtMapDirections.routeMode .routeModeButton.active {
    color: #44F;
    border-color: #44F;
  }

  .rExtMapDirections.routeList {
    overflow: auto;
    padding: 10px;
    height: 250px;
  }
  .rExtMapDirections.routeList .adp-directions {
    width: 100%;
  }
</style>


{capture rExtMapDirections assign="jsMapDirectionsForm"}
<div class="rExtMapDirections dirForm jsMapDirectionsForm">
  <p class="mapRouteFormTitle">{t}Type a departure address{/t}:</p>
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
</div>
{/capture}

{capture rExtMapDirections assign="jsMapDirectionsShow"}
<div class="rExtMapDirections tabList jsMapDirectionsShow">{t}Show route description{/t} <i class="fa fa-sort-down"></i><i class="fa fa-sort-up" style="display:none;"></i></div>
{/capture}

{capture rExtMapDirections assign="jsMapDirectionsMode"}
<div class="rExtMapDirections routeMode jsMapDirectionsMode">
  <div data-route-mode="0" class="routeModeButton active"><i data-route-mode="0" class="fa fa-car fa-fw"></i></div>
  <div data-route-mode="1" class="routeModeButton"><i data-route-mode="1" class="fa fa-male fa-fw"></i></div>
  <div data-route-mode="2" class="routeModeButton"><i data-route-mode="2" class="fa fa-bus fa-fw"></i></div>
  <span class="routeInfo jsMapDirectionsInfo">{t}Route information{/t}</span>
</div>
{/capture}

{capture rExtMapDirections assign="jsMapDirectionsList"}
<div id="jsMapDirectionsList" class="rExtMapDirections routeList jsMapDirectionsList"></div>
{/capture}

{capture rExtMapDirections assign="jsMapDirectionsInMap"}
<div class="rExtMapDirections wrapperInMap jsMapDirectionsInMap">
  <div class="prevBar jsMapDirInMapBar">
    <div class="mapDirInMapClose jsMapDirInMapClose">{t}Close{/t} <span class="fa fa-window-close" aria-hidden="true"></span></div>
    {$jsMapDirectionsMode}
  </div>
  {$jsMapDirectionsList}
  <div class="postBar jsMapDirInMapBar">
    <div class="mapDirInMapPrint jsMapDirInMapPrint">{t}Print{/t} <span class="fa fa-print" aria-hidden="true"></span></div>
  </div>
</div>
{/capture}



<div class="rExtMapDirections">
  <div class="mapRoute">
    <div class="container resDirContainer jsContainer">
      {$jsMapDirectionsForm}
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
    // wrapperMap: '.resMapContainer',
    // wrapperRoute: '.rExtMapDirections .mapRoute',
    html: {
      jsMapDirectionsInMap: "{$jsMapDirectionsInMap|escape:'javascript'}",
      jsMapDirectionsForm: "{$jsMapDirectionsForm|escape:'javascript'}",
      jsMapDirectionsMode: "{$jsMapDirectionsMode|escape:'javascript'}",
      jsMapDirectionsShow: "{$jsMapDirectionsShow|escape:'javascript'}",
      jsMapDirectionsList: "{$jsMapDirectionsList|escape:'javascript'}"
    },
    scrollTopMargin: 130
  };
</script>
<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
