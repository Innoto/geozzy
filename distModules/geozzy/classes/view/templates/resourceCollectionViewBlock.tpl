<h4>{$collectionResources.col.title}</h4>
<div >{t}A continuación te mostramos algunos recursos que pensamos que pueden interesarte{/t}</div>
<div class="collectionBox owl-carousel">
    {foreach $collectionResources.res as $resId => $res}
      <div class="item">
        <div class="itemImage">
          <div class="trama"></div>
          <img class="img-responsive" alt="{$res.title}" src="{$res.image}" data-image="{$res.image_big}" data-description="{$res.title}">
          <div class="destResourceMoreInfo">
            <a href="/resource/{$res.id}">
              <p>{$res.shortDescription}</p>
            </a>
            <a class="btn btn-primary" href="/resource/{$res.id}">{t}Coñéceo{/t}</a>
          </div>
        </div>
        <div class="itemTitle">
          <a href="/resource/{$res.id}">
            <h3>{$res.title}</h3>
          </a>
        </div>
      </div>
    {/foreach}
</div>
