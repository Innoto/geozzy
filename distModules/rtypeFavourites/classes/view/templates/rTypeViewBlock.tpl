{block name="headCssIncludes" append}
<style type="text/css">
  .favouritesElement img { max-width: 150px; max-height: 150px; }
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
          <div class="col-sm-6 col-md-3 favouritesElement">
            <a href="{$favsResInfo.url}"><img src="/cgmlImg/{$favsResInfo.image}/fast/{$favsResInfo.image}.jpg"></a>
            <br><a href="{$favsResInfo.url}">{$favsResInfo.title}</a>
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
