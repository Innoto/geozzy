
<script>
  var multimedia = '';
  {foreach $multimediaResourcesAll as $multiId => $multimedia}
  multimedia = multimedia + '<img alt="{$multimedia['name']}" src="/cgmlImg/{$multimedia['img']}/typeIconMini/{$multimedia['img']}.jpg"'+
      'data-image="/cgmlImg/{$multimedia['img']}/typeIconMini/{$multimedia['img']}.jpg"'+
      'data-description="{$multimedia['name']}">';
  {/foreach}

  var recursos = '';
  {foreach $collectionResourcesAll as $resId => $res}
  recursos = recursos + '<img alt="{$res['name']}" src="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"'+
      'data-image="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"'+
      'data-description="{$res['name']}">';
  {/foreach}
</script>

<div id="multimediaGallery" style="display:none;">
    {foreach $multimediaResources as $multiId => $multimedia}
      <img alt="{$multimedia['name']}" src="/cgmlImg/{$multimedia['img']}/typeIconMini/{$multimedia['img']}.jpg"
        data-image="/cgmlImg/{$multimedia['img']}/typeIconMini/{$multimedia['img']}.jpg"
        data-description="{$multimedia['name']}">
    {/foreach}
</div>

<div id="collectionsGallery" style="display:none;">
    {foreach $collectionResources as $resId => $res}
      <img alt="{$res['name']}" src="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"
        data-image="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"
        data-description="{$res['name']}">
    {/foreach}
</div>
