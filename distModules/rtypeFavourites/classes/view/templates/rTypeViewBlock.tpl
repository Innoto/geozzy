{block name="headCssIncludes" append}
<style type="text/css">
  .favouritesElement .favsImage { position: relative; }
  .favouritesElement .favsImage img { width: 100%; }
  .favouritesElement .favsImage .favsDelete {
    position: absolute;
    right: 10px;
    bottom: 10px;
    display: block;
    padding: 2px 4px;
    background-color: white;
    color: #FF2222;
    font-size: 20px;
    cursor: pointer;
  }
</style>
{/block}



<!-- rTypeViewBlock.tpl en rTypeFavourites module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-9 col-md-10">
          {*<img class="iconTitleBar img-fluid" alt="Páxina xeral" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>*}
          <h1>{t}Favourites page{/t}</h1>
        </div>
      </div>
    </div>
  </div>

  <section class="contentSec container gzSection">
    <div class="content">
      {$res.data.content}
    </div>
  </section>

  <script type="text/javascript">
    var geozzy = geozzy || {};
    geozzy.favsResourcesInfo = {$favsResourcesInfo|@json_encode}
  </script>
  <section class="favouritesSec gzSection">
    <div class="container favsHeader">
    </div>

    <div class="container favsList">
      <div class="favouritesList">

        {if $favsResourcesInfo|@is_array && $favsResourcesInfo|@count gt 0}
        <div class="row row-eq-height">
          {foreach $favsResourcesInfo as $favsResInfo}
          {* title shortDescription image url rTypeId *}
          <div class="col-12 col-sm-6 col-md-3 favouritesElement rtid{$favsResInfo.rTypeId}" data-id="{$favsResInfo.id}" data-rTypeId="{$favsResInfo.rTypeId}">
            <div class="favsImage">
              <a href="{$favsResInfo.url}"><img src="/cgmlImg/{$favsResInfo.image}-a{$favsResInfo.imageAKey}/{$favsResInfo.perfilFavouriteImg}/{$favsResInfo.imageName}"></a>
            </div>
            <div class="favsText">
            <a href="{$favsResInfo.url}">
              <div class="title">{$favsResInfo.title}</div>
              <div class="shortDescription">{$favsResInfo.shortDescription}</div>
            </a>
            </div>
          </div>
          {/foreach}
        </div>
        {else}
        <div class="row">
          <div class="favouritesEmpty">
            <p>{t}It seems you haven't added any items to your favorites list yet...{/t}</p>
          </div>
        </div>
        {/if}

      </div>
    </div>

    <div class="container favsFooter">
    </div>
  </section>


</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeFavourites module -->
