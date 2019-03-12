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

<!-- rTypeViewBlock.tpl en rTypePage module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-fluid" alt="Páxina xeral" src="{$cogumelo.publicConf.media}/img/paxinaIcon.png"></img>
          <h1>{$res.data.title}</h1>
        </div>
        {if isset($stars)}
        <div class="stars d-none col-sm-3 col-md-2">
          <i class="far fa-star"></i>
          <i class="far selected fa-star"></i>
          <i class="far selected fa-star"></i>
          <i class="far selected fa-star"></i>
          <i class="far selected fa-star"></i>
        </div>
        {/if}
      </div>
    </div>
  </div>

  {if $res.data.image.id}
  <section class="imageSec gzSection">

  </section>
  {/if}

  <section class="contentSec container gzSection">
    <div class="typeBar row">
      <ul class="social col-12 col-sm-12 col-md-12 clearfix">
        <li class="elementShare">
          {if isset($res.ext.rextSocialNetwork) && (isset($res.ext.rextSocialNetwork.data.activeFb) || isset($res.ext.rextSocialNetwork.data.activeTwitter))}
            <div class="share"><i class="fas fa-share-alt"></i></div>
            <div class="share-open" style="display:none;">
              {if isset($res.ext.rextSocialNetwork.data.activeFb) && $res.ext.rextSocialNetwork.data.activeFb}
                <div class="share-net fb">
                  <a class="icon-share facebook" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u={$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}&t={$res.ext.rextSocialNetwork.data["textFb"]}">
                      <i class="fab fa-facebook-square"></i>
                  </a>
                </div>
              {/if}
              {if isset($res.ext.rextSocialNetwork.data.activeTwitter) && $res.ext.rextSocialNetwork.data.activeTwitter}
                <div class="share-net twitter">
                  <a class="icon-share twitter" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$res.data["urlAlias"]}&text={$res.ext.rextSocialNetwork.data["textTwitter"]}">
                    <i class="fab fa-twitter-square"></i>
                  </a>
                </div>
              {/if}
            </div>
          {/if}
        </li>
        {if isset($fav)}
        <li class="elementFav">
          <i class="far fa-heart"></i>
          <i class="fas fa-heart"></i>
        </li>
        {/if}
      </ul>
    </div>

    <div class="mediumDescription">
      {$res.data.mediumDescription}
    </div>

    <div class="content">
      {$res.data.content}
    </div>
  </section>

  {if (isset($rextContactBlock) && $rextContactBlock!="") || (isset($res.ext.rextContact.data.directions) && $res.ext.rextContact.data.directions!== "")
  || ( isset( $res.ext.rextMapDirections.data ) && $res.ext.rextMapDirections.data !=="")}
  <section class="locationSec gzSection">
    {if (isset($rextContactBlock) && $rextContactBlock!="")}
    <div class="locationLight">
      <div class="location container">
        <div class="title">
          {t}Contact{/t}
        </div>
        <div class="{$res.data.rTypeIdName} accommodation">
          {$rextContactBlock}
        </div>
      </div>
    </div>
    {/if}

    <div class="locationDark">
      {if (isset($res.ext.rextContact.data.directions) && $res.ext.rextContact.data.directions!== "")
      || ( isset( $res.ext.rextMapDirections.data ) && $res.ext.rextMapDirections.data !=="")}
      <div class="directions">
        <div class="container">
          <div class="title">
            {t}See indications{/t} <i class="fas fa-sort-down"></i>
          </div>
          {if isset( $res.data.loc )}
          <div class="indications row" style="display:none;">
            <div class="col-lg-8">
              {$res.ext.rextContact.data.directions|escape:'htmlall'|nl2br}
            </div>
            <div class="col-lg-4">
              <div class="search">
                {t}How to arrive from?{/t} <i class="fas fa-search"></i>
              </div>
            </div>
          </div>
          {else}
          <div class="indications" style="display:none;">
            {$res.ext.rextContact.data.directions|escape:'htmlall'|nl2br}
          </div>
          {/if}
        </div>
      </div>
      {/if}
    </div>

    {if isset( $res.data.loc )}
    <div class="map">
      <div class="container">

      </div>
    </div>
    {/if}

    {if (isset($collections) || isset($multimediaGalleries)) }
      <div class="grey-bar"></div>
    {/if}
  </section>
  {/if}
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

</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypePage module -->
