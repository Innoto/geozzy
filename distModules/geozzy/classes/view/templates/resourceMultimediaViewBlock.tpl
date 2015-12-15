<script>
  idGallery.push('{$id}');
  data = '';
  {foreach $multimediaAll.res as $multimedia}
  data = data + '<img alt="{$multimedia.title}" src="/cgmlImg/{$multimedia.image}/typeIconMini/{$multimedia.image}.jpg" '+
      'data-image="/cgmlImg/{$multimedia.image}/typeIconMini/{$multimedia.image}.jpg" '+
      'data-description="{$multimedia.title}">';
  {/foreach}
  multimedia.push({
    id : '{$id}',
    firstLoad: true,
    html : data
  });
</script>

<h4>{$multimediaFirst.col.title}</h4>
<div id="multimediaGallery_{$id}" class="simpleMultimediaGallery" style="display:none;">
  {foreach $multimediaFirst.res as $multimedia}
    <img alt="{$multimedia.title}" src="/cgmlImg/{$multimedia.image}/typeIconMini/{$multimedia.image}.jpg"
        data-image="/cgmlImg/{$multimedia.image}/typeIconMini/{$multimedia.image}.jpg"
        data-description="{$multimedia.title}">
  {/foreach}
</div>
<div id="more_{$id}" class="more">{t}Ver más...{/t}</div>
<div id="multimediaAllGallery_{$id}" style="display:none;"></div>
<div id="less_{$id}" class="less" style="display:none;">{t}Ver menos{/t}</div>  
