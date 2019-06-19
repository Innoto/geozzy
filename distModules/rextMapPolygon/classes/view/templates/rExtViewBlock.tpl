<!-- rExtViewBlock.tpl en rExtMap module -->
<style type="text/css">

</style>

<script type="text/javascript">
  var geozzy = geozzy || {};
  {if !empty($rExt.data.polygonGeometry) }
    geozzy.rExtMapPolygonCoords = [{$rExt.data.polygonGeometry}];
  {else}
    geozzy.rExtMapPolygonCoords = false;
  {/if}
</script>
<!-- /rExtViewBlock.tpl en rExtMap module -->
