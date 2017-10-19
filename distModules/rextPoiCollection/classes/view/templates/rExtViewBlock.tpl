<script type="text/javascript">
  var geozzy = geozzy || {};

  geozzy.rExtPOIOptions = {
    showPanorama:false,
    resourceID: {$rExt.data.id}
  };



</script>

<style>
  {literal}
    .panorama-custom-hotspot {
        height: 20px;
        width: 20px;
        border-radius: 50%;
        background: #f00;
        border:2px solid white;
    }

    .panorama-custom-hotspot-selected {
      border-width: 5px;
      margin-left:-2px;
      margin-top:-2px;
    }
  {/literal}
</style>
<div id="panoramaView"  style="display:none;height:400px;background-color:grey;"></div>
