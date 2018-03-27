<!-- rExtViewBlock.tpl en rExtMapDirections module -->
<style type="text/css">
  {* Basura V.1 *}
  .rExtMapDirections.tabList {
    display: none;
  }

  .rExtMapDirections.wrapperInMap {
    padding: 0;
    background-color: white;
  }

  .rExtMapDirections.wrapperInMap .barInMap {
    border: 1px solid #CCC;
    border-left-style: none;
    border-right-style: none;
    /*padding: 5px 0;*/
  }

  .rExtMapDirections .mapDirInMapClose,
  .rExtMapDirections .mapDirInMapPrint {
    padding: 5px 20px;
    text-align: right;
  }
  .rExtMapDirections .mapDirInMapClose .fa,
  .rExtMapDirections .mapDirInMapPrint .fa {
    font-size: 2em;
    cursor: pointer;
  }

  .rExtMapDirections .mapDirInMapPrint a{
    color: #444;
  }

  .rExtMapDirections.routeMode {
    position: relative;
    padding: 5px 20px;
  }
  .rExtMapDirections.routeMode .routeModeButton {
    display: inline-block;
    padding: 1px 2px;
    border: 2px solid #444;
    border-radius: 0.2em;
    color: #444;
    cursor: pointer;
  }
  .rExtMapDirections.routeMode .routeModeButton .fa {
    font-size: 28px;
  }
  .rExtMapDirections.routeMode .routeModeButton.active {
    color: #44F;
    border-color: #44F;
  }

  .rExtMapDirections .routeInfo {
    position: absolute;
    display: inline-block;
    bottom: 3px;
    right: 20px;
    font-size: 1.1em;
  }

  .rExtMapDirections.routeList {
    overflow: auto;
    padding: 10px;
    margin: 0 10px;
    height: 250px;
  }
  .rExtMapDirections.routeList .adp-directions {
    width: 100%;
  }
</style>


{capture rExtMapDirections assign="jsMapDirectionsForm"}
<div class="rExtMapDirections dirForm jsMapDirectionsForm">
  <div class="row">
    <div class="col-md-12">
      <p class="mapRouteFormTitle">{t}Type a departure address{/t}:</p>
    </div>
  </div>
  <form class="mapRouteForm">
    <div class="row">
      <div class="col-md-4">
        <input name="mapRouteOrigin" aria-label="{t}Type a departure address{/t}" placeholder="{t}Type a departure address{/t}">
      </div>
      <div class="col-md-4">
        <button aria-label="{t}Get directions{/t}" type="submit">{t}Get directions{/t}</button>
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
  <div data-route-mode="0" class="routeModeButton active" data-toggle="tooltip" data-placement="top" title="{t}Route by car{/t}"><i data-route-mode="0" class="fa fa-car fa-fw"></i></div>
  <div data-route-mode="1" class="routeModeButton" data-toggle="tooltip" data-placement="top" title="{t}Walking route{/t}"><i data-route-mode="1" class="fa fa-male fa-fw"></i></div>
  <div data-route-mode="2" class="routeModeButton" data-toggle="tooltip" data-placement="top" title="{t}Route by public transport{/t}"><i data-route-mode="2" class="fa fa-bus fa-fw"></i></div>
  <span class="routeInfo jsMapDirectionsInfo">{t}Route information{/t}</span>
</div>
{/capture}

{capture rExtMapDirections assign="jsMapDirectionsList"}
<div id="jsMapDirectionsList" class="rExtMapDirections routeList jsMapDirectionsList"></div>
{/capture}

{capture rExtMapDirections assign="jsMapDirectionsInMap"}
<div class="rExtMapDirections wrapperInMap jsMapDirectionsInMap">
  <div class="prevBar barInMap jsMapDirInMapBar">
    <div class="mapDirInMapClose jsMapDirInMapClose"><span class="button fa fa-window-close" aria-hidden="true"></span></div>
    {$jsMapDirectionsMode}
  </div>
  {$jsMapDirectionsList}
  <div class="postBar barInMap jsMapDirInMapBar">
    <div class="mapDirInMapPrint jsMapDirInMapPrint">
      <a href="#" title="{t}Print directions{/t}" target="_blank" rel="noopener noreferrer">
        <span class="button fa fa-print" aria-hidden="true" data-toggle="tooltip" data-placement="left" title="{t}Print directions{/t}"></span>
      </a>
    </div>
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
