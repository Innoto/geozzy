<h4>{$collectionResources.col.title}</h4>
<div >{t}A continuación te mostramos algunos recursos que pensamos que pueden interesarte{/t}</div>
<div class="collectionBox owl-carousel">
    {foreach $collectionResources.res as $resId => $res}
      <div class="item">
        <div class="itemImage">

            <img class="img-responsive" alt="{$res.title}" src="{$res.image}" data-image="{$res.image_big}" data-description="{$res.title}">
            <div class="trama">
                <div class="destResourceMoreInfo">
                  <p>{$res.shortDescription}</p>
                  <a class="btn btn-primary" href="/{$res.urlAlias}">{t}Descúbreo{/t}</a>
                </div>
            </div>

        </div>
        <div class="itemTitle">
          <a target="_blank" href="/{$res.urlAlias}">
            <h3>{$res.title}</h3>
          </a>
        </div>
      </div>
    {/foreach}
</div>
