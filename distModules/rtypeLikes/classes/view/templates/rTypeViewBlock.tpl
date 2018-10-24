{block name="headCssIncludes" append}
<style type="text/css">
  .likesElement .likesImage { position: relative; }
  .likesElement .likesImage img { width: 100%; }
  .likesElement .likesImage .likesDelete {
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



<!-- rTypeViewBlock.tpl en rtypeLikes module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          {*<img class="iconTitleBar img-fluid" alt="Páxina xeral" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>*}
          <h1>{t}Likes page{/t}</h1>
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
    geozzy.likesResourcesInfo = {$likesResourcesInfo|@json_encode}
  </script>
  <section class="likesSec gzSection">
    <div class="container likesHeader">
    </div>

    <div class="container likesList">
      <div class="likesList">

        {if $likesResourcesInfo|@is_array && $likesResourcesInfo|@count gt 0}
        <div class="row row-eq-height">
          {foreach $likesResourcesInfo as $likesResInfo}
          {* title shortDescription image url rTypeId *}
          <div class="col-xs-12 col-sm-6 col-md-3 likesElement rtid{$likesResInfo.rTypeId}" data-id="{$likesResInfo.id}" data-rTypeId="{$likesResInfo.rTypeId}">
            <div class="likesImage">
              <a href="{$likesResInfo.url}"><img src="/cgmlImg/{$likesResInfo.image}-a{$likesResInfo.imageAKey}/{$likesResInfo.perfilLikeImg}/{$likesResInfo.imageName}"></a>
            </div>
            <div class="likesText">
            <a href="{$likesResInfo.url}">
              <div class="title">{$likesResInfo.title}</div>
              <div class="shortDescription">{$likesResInfo.shortDescription}</div>
            </a>
            </div>
          </div>
          {/foreach}
        </div>
        {else}
        <div class="row">
          <div class="likesEmpty">
            <p>{t}It seems you haven't added any items to your likes list yet...{/t}</p>
          </div>
        </div>
        {/if}

      </div>
    </div>

    <div class="container likesFooter">
    </div>
  </section>


</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rtypeLikes module -->
