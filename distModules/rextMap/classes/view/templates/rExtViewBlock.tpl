<!-- rExtViewBlock.tpl en rExtMapDirections module -->

<style type="text/css">

  .rExtMapDirections .resDirContainer .routeModeButton.active {
    color: #44F;
    border-color: #44F;
  }
  .rExtMapResource .resMapWrapper {
    height:400px;
  }
  .rExtMapResource .resMapContainer {
    width:100%;
    height:100%;
  }
</style>

<div class="rExtMapResource">
  <div class="resMapWrapper">
    <div class="resMapContainer" style="">
      <!-- google.map -->
    </div>
  </div>
</div>


<!--<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>-->
<script type="text/javascript">
var geozzy = geozzy || {};


geozzy.rExtMapOptions = {
    lat: {$rExt.data.locLat},
    lng: {$rExt.data.locLon},
    zoom: {$rExt.data.defaultZoom},
    wrapper: '.rExtMapResource .resMapContainer'
  };



</script>

<!-- /rExtViewBlock.tpl en rExtMapDirections module -->
