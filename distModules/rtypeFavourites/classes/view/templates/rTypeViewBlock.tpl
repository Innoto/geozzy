{block name="headCssIncludes" append}
<style type="text/css">
  .favouritesElement { height: 250px; }
  .favouritesElement img { width: 100%; }
  .favDelete {
    position: absolute;
    right: 20px;
    top: 5px;
    display: block;
    padding: 2px;
    /* background-color: yellow; */
    color: #FF2222;
    font-size: 20px;
  }
  .favouritesElement .title {
    text-decoration: underline;
  }
  .favouritesElement .shortDescription {
    font-size: 90%
  }
</style>
{/block}

<!-- rTypeViewBlock.tpl en rTypeFavourites module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Páxina xeral" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
          <h1>Tus favoritos</h1>
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
  <section class="favouritesSec container gzSection">
    <div class="favourites">
      <div class="container">

        {if $favsResourcesInfo|@is_array && $favsResourcesInfo|@count gt 0}
        <div class="row">
          {foreach $favsResourcesInfo as $favsResInfo}
          {* title shortDescription image url rTypeId *}
          <div class="col-sm-6 col-md-2 favouritesElement rtid{$favsResInfo.rTypeId}" data-id="{$favsResInfo.id}" data-rTypeId="{$favsResInfo.rTypeId}">
            <a href="{$favsResInfo.url}">
              <img style="whi" src="/cgmlImg/{$favsResInfo.image}/fast_cut/{$favsResInfo.image}.jpg">
              <div class="title">{$favsResInfo.title}</div>
              <div class="shortDescription">{$favsResInfo.shortDescription}</div>
            </a>
          </div>
          {/foreach}
        </div>
        {else}
        <div class="row">
          <div class="favouritesEmpty">
            <p>Parece que todavía no has añadido ningún elemento a tu lista de favoritos...</p>
          </div>
        </div>
        {/if}

      </div>
    </div>
  </section>


</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeFavourites module -->
