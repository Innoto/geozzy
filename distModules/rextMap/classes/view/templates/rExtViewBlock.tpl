<!-- rExtViewBlock.tpl en rExtMap module -->
<style type="text/css">
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

<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtMapOptions = {
    lat: {$rExt.data.locLat},
    lng: {$rExt.data.locLon},
    zoom: {$rExt.data.defaultZoom},
    wrapper: '.rExtMapResource .resMapContainer'
  };
</script>
<!-- /rExtViewBlock.tpl en rExtMap module -->
