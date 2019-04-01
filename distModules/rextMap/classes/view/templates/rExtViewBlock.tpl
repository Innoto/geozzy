<!-- rExtViewBlock.tpl en rExtMap module -->
<style type="text/css">
  .rExtMapResource {
    height:400px;
  }
  .rExtMapResource .resMapWrapper,
  .rExtMapResource .resMapContainer {
    width:100%;
    height:100%;
  }
</style>



  {if $rExt.data.visible}
  <div class="rExtMapResource">
    <div class="resMapWrapper">
      <div class="resMapContainer" style="">
        <!-- google.map -->
      </div>
    </div>
  </div>
  {/if}

<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtMapOptions = {
    lat: {$rExt.data.locLat},
    lng: {$rExt.data.locLon},
    zoom: {$rExt.data.defaultZoom},
    wrapper: {if $rExt.data.visible}'.rExtMapResource .resMapContainer'{else}false{/if},
    resourceRtypeIdName: '{$rExt.data.rTypeIdName}'
  };
</script>
<!-- /rExtViewBlock.tpl en rExtMap module -->
