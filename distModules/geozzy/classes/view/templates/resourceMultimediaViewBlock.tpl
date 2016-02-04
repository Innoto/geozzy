<script>

  if ( typeof idGallery === 'undefined' ) {
    var idGallery = [];
    var multimedia = [];
  }

  if (idGallery.indexOf('{$id}') == '-1'){
    idGallery.push('{$id}');

    data = '';
    {foreach $multimediaAll.res as $multimedia}
      {if $multimedia.multimediaUrl}
        data = data + '<img alt="Youtube Without Images" src="{$multimedia.image}"'+
          'data-type="youtube"'+
          'data-image="{$multimedia.image_big}"'+
          'data-videoid="{$multimedia.multimediaUrl}"'+
          'data-description="Youtube video description">';
      {else}
        data = data + '<img alt="{$multimedia.title}" src="{$multimedia.image}" '+
          'data-image="{$multimedia.image_big}" '+
          'data-description="{$multimedia.title}">';
      {/if}

    {/foreach}
    multimedia.push({
      id : '{$id}',
      firstLoad: true,
      html : data
    });
  }


</script>

<div class="galleryBox">
<h4>{$multimediaAll.col.title}</h4>
<div>{$multimediaAll.col.shortDescription}</div>
  <div id="multimediaGallery_{$id}" class="simpleMultimediaGallery" style="display:none;">
    {assign var=counter value=0}
    {foreach $multimediaAll.res as $multimedia}
      {if $counter<$max}
        {if $multimedia.multimediaUrl}
          <img alt="Youtube Without Images" src="{$multimedia.image}"
            data-type="youtube"
            data-image="{$multimedia.image_big}"
            data-videoid="{$multimedia.multimediaUrl}"
            data-description="Youtube video description">
        {else}
          <img alt="{$multimedia.title}" src="{$multimedia.image}"
            data-image="{$multimedia.image_big}"
            data-description="{$multimedia.title}">
        {/if}
      {/if}
      {capture assign=counter}{$counter+1}{/capture}
    {/foreach}
  </div>
  {if $counter>$max}
    <div id="more_{$id}" class="more">{t}See more...{/t}</div>
    <div id="multimediaAllGallery_{$id}" style="display:none;"></div>
    <div id="less_{$id}" class="less" style="display:none;">{t}Less{/t}</div>
  {/if}
</div>
