<script>
  idGallery.push('{$id}');
  data = '';
  {foreach $multimediaAll.res as $multimedia}
  data = data + '<img alt="{$multimedia.title}" src="{$multimedia.image}" '+
      'data-image="{$multimedia.image_big}" '+
      'data-description="{$multimedia.title}">';
  {/foreach}
  multimedia.push({
    id : '{$id}',
    firstLoad: true,
    html : data
  });
</script>

<div class="galleryBox">
<h4>{$multimediaAll.col.title}</h4>
  <div id="multimediaGallery_{$id}" class="simpleMultimediaGallery" style="display:none;">
    {assign var=counter value=1}
    {foreach $multimediaAll.res as $multimedia}
      {if $counter<5}
      <img alt="{$multimedia.title}" src="{$multimedia.image}"
          data-image="{$multimedia.image_big}"
          data-description="{$multimedia.title}">
          {capture assign=counter}{$counter+1}{/capture}
      {/if}
    {/foreach}
  </div>
  {if $counter>4}
    <div id="more_{$id}" class="more">{t}Ver más...{/t}</div>
    <div id="multimediaAllGallery_{$id}" style="display:none;"></div>
    <div id="less_{$id}" class="less" style="display:none;">{t}Ver menos{/t}</div>
  {/if}
</div>
