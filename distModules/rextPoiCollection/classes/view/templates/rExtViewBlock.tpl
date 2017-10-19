<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtPOIOptions = {
    showPanorama: false,
    panoramaImg: false,
    resourceID: {$rExt.data.id}
  };



</script>

<style>
  {literal}
    .panorama-custom-hotspot {
        height: 15px;
        width: 15px;
        border-radius: 50%;
        background: #D35E5E;
        border:2px solid white;
    }

    .panorama-custom-hotspot-selected {
      border-width: 3px;
      margin-left:-1px;
      margin-top:-1px;
    }
  {/literal}
</style>
<div id="panoramaView"  style="display:none;height:350px;background-color:grey;"></div>
