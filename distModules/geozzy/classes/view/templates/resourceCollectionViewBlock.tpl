
<script>

  var recursos = '';
  {foreach $collectionResourcesAll as $resId => $res}
  recursos = recursos + '<img alt="{$res['name']}" src="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"'+
      'data-image="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"'+
      'data-description="{$res['name']}">';
  {/foreach}
</script>


UH
<div id="collectionsGallery" style="display:none;">
    {foreach $collectionResources as $multiId => $multimediaVal}
    OLA
      <h4>{$multimediaVal.col.title}</h4>
      {foreach $multimediaVal.res as $multimedia}
      <img alt="{$multimedia['title']}" src="/cgmlImg/{$multimedia['image']}/typeIconMini/{$multimedia['image']}.jpg"
        data-image="/cgmlImg/{$multimedia['image']}/typeIconMini/{$multimedia['image']}.jpg"
        data-description="{$multimedia['title']}">
      {/foreach}
    {/foreach}
</div>

<!-- <div class="owl-carousel">
    {foreach $collectionResourcesAll as $resId => $res}
      <div class="item">
        <img alt="{$res['name']}" src="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg" data-image="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg" data-description="{$res['name']}">
      </div>
    {/foreach}
</div> -->
