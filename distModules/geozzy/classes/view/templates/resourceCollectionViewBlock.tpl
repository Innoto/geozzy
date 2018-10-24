<h4>{$collectionResources.col.title}</h4>
<div>{$collectionResources.col.shortDescription}</div>
<div class="collectionBox owl-carousel owl-carousel-gzz">
    {foreach $collectionResources.res as $resId => $res}
      <div class="item isResource" data-related-resource-id="{$res.id}">
        <div class="itemImage">

            <img class="img-fluid" alt="{$res.title}" src="{$res.image}" data-image="{$res.imagebig|default:''}" data-description="{$res.title}">
            <div class="trama">
                <div class="destResourceMoreInfo">
                  <p>{$res.shortDescription}</p>
                  <a class="btn btn-primary" href="{$res.urlAlias}">{t}Find out!{/t}</a>
                </div>
            </div>

        </div>
        <div class="itemTitle">
          <a href="{$res.urlAlias}">
            <h3>{$res.title}</h3>
          </a>
        </div>
      </div>
    {/foreach}
</div>
