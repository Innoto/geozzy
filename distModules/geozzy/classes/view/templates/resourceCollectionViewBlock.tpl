
<div id="collectionsGallery" style="display:none;">
    {foreach $collectionResources as $resId => $res}
      <img alt="{$res['name']}" src="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"
        data-image="/cgmlImg/{$res['img']}/typeIconMini/{$res['img']}.jpg"
        data-description="{$res['name']}">
    {/foreach}
</div>
