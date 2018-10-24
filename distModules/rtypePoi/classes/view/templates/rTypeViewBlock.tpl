{block name="headCssIncludes" append}
<style type="text/css">
  .imageSec{
    background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    height: 50vh;
  }
  @media screen and (min-width: 1200px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/resourceLg/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    }
  } /*1200px*/

  @media screen and (max-width: 1199px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/resourceMd/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    }
  }/*1199px*/

  @media screen and (max-width: 991px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/resourceSm/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    }
  }/*991px*/
</style>
{/block}

<script>

</script>

<!-- rTypeViewBlock.tpl en rTypePoi module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-fluid" alt="Aloxamentos con encanto" src="{$cogumelo.publicConf.media}/img/aloxamentosIcon.png"></img>
          <h1>{$res.data.title}</h1>
        </div>
        <div class="stars hidden-xs col-sm-3 col-md-2">
          <i class="far fa-star"></i>
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
        </div>
      </div>
    </div>
  </div>

  <section class="imageSec gzSection">
    <div class="reservationSec container">
      <a href="javascript:history.go(-1);" class="backExplorer">
        <div class="arrow-left"></div>
        <p>{t}volver{/t}</p>
      </a>
    </div>
  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar row">
      <ul class="type col-xs-6 col-sm-6 col-md-6 clearfix">
        {if isset($res.data.poiType)}
        {foreach from=$res.data.poiType item=termInfo}
          <li>
            {if isset($termInfo.icon)}<img src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$termInfo.icon}/typeIconMini/{$termInfo.icon}.svg" />{/if}
            {$l = $cogumelo.publicConf.C_LANG}
            <div class="name">{$termInfo["name_$l"]}</div>
          </li>
          {break}
        {/foreach}
        {/if}
      </ul>

      <ul class="social col-xs-6 col-sm-6 col-md-6 clearfix">
        <li class="elementShare">
          {if isset($res.ext.rextSocialNetwork) && ($res.ext.rextSocialNetwork.data.activeFb || $res.ext.rextSocialNetwork.data.activeTwitter)}
            <div class="share"><i class="fas fa-share-alt"></i></div>
            <div class="share-open" style="display:none;">
              {if isset($res.ext.rextSocialNetwork.data.activeFb) && $res.ext.rextSocialNetwork.data.activeFb}
                <div class="share-net fb">
                  <a class="icon-share facebook" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u={$site_host}{$res.data["urlAlias"]}&t={$res.ext.rextSocialNetwork.data["textFb"]}">
                      <i class="fab fa-facebook-square"></i>
                  </a>
                </div>
              {/if}
              {if isset($res.ext.rextSocialNetwork.data.activeTwitter) && $res.ext.rextSocialNetwork.data.activeTwitter}
                <div class="share-net twitter">
                  <a class="icon-share twitter" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$site_host}{$res.data["urlAlias"]}&text={$res.ext.rextSocialNetwork.data["textTwitter"]}">
                    <i class="fab fa-twitter-square"></i>
                  </a>
                </div>
              {/if}
            </div>
          {/if}
        </li>
        <li class="elementFav">
          <i class="far fa-heart"></i>
          <i class="fas fa-heart"></i>
        </li>
      </ul>
    </div>

    <div class="mediumDescription">
      {$res.data.mediumDescription}
    </div>

    <div class="content">
      {$res.data.content}
    </div>
  </section>

  <section class="locationSec gzSection">
    {if (isset($rextContactBlock) && $rextContactBlock!="")}
    <div class="locationLight">
      <div class="location container">
        <div class="title">
          {t}Contact{/t}
        </div>
        <div class="{$res.data.rTypeIdName} poi">
          {$rextContactBlock}
        </div>
      </div>
    </div>
    {/if}


    {if (isset($collections) || isset($multimediaGalleries)) }
      <div class="grey-bar"></div>
    {/if}
  </section>

  {if isset($multimediaGalleries)}
    <section class="multimediaSec container gzSection">
      {$multimediaGalleries}
    </section>
  {/if}

  {if isset($collections)}
    <section class="collectionSec container gzSection">
      {$collections}
    </section>
  {/if}

</div>
<!-- /rTypeViewBlock.tpl en rTypePoi module -->
