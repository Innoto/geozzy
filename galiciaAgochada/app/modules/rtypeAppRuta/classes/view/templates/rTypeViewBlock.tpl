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

{$rextBIBlock}

<!-- rTypeViewBlock.tpl en rTypeEspazoNatural module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}" data-resource="{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Rutas" src="{$cogumelo.publicConf.media}/img/rutasIcon.png"></img>
          <h1>{$res.data.title}</h1>
        </div>
        {if isset($rextCommentAverageBlock)}
        <div class="stars hidden-xs col-sm-3 col-md-2">
          {$rextCommentAverageBlock}
        </div>
        {/if}
      </div>
    </div>
  </div>

  <section class="imageSec gzSection">

    <div class="reservationSec container">
      <a href="javascript:history.go(-1);" class="backExplorer">
        <div class="arrow-left"></div>
        <p>{t}back{/t}</p>
      </a>

      <div class="resumeBox">
        <div class="topBox">
          <div class="travelDistance col-md-6">
            {$res.ext.rextRoutes.data.travelDistanceKm} Km
          </div>
          <div class="durationMinutes col-md-6">{$res.ext.rextRoutes.data.durationHours}h {$res.ext.rextRoutes.data.durationMinutes}min</div>
        </div>
        <div class="bottomBox">
          <div class="difficultyGlobal col-md-6">
            <div class="barraEsfuerzo ruta_{$res.ext.rextRoutes.data.difficultyGlobal}"></div>
            <div class="text">{t}Global difficulty{/t}</div>
          </div>
          <div class="circular col-md-6">
            {if $res.ext.rextRoutes.data.circular}
              <div class="rutaCircular">
                <img class="img-responsive" alt="circular" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/circular_color.png"></img>
                <div class="text">{t}Circular{/t}</div>
              </div>
            {else}
              <div class="rutaLineal">
                <img class="img-responsive" alt="lineal" src="{$cogumelo.publicConf.media}/module/rextRoutes/img/lineal_color.png"></img>
                <div class="text">{t}Lineal{/t}</div>
              </div>
            {/if}
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar">

      <ul class="social clearfix">
        <li class="elementShare">
          {if isset($res.ext.rextSocialNetwork) && ($res.ext.rextSocialNetwork.data.activeFb || $res.ext.rextSocialNetwork.data.activeTwitter)}
            <div class="share"><i class="fa fa-share-alt"></i></div>
            <div class="share-open" style="display:none;">
              {if isset($res.ext.rextSocialNetwork.data.activeFb) && $res.ext.rextSocialNetwork.data.activeFb}
                <div class="share-net fb">
                  <a class="icon-share facebook" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u={$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}&t={$res.ext.rextSocialNetwork.data["textFb"]}">
                      <i class="fa fa-facebook-square"></i>
                  </a>
                </div>
              {/if}
              {if isset($res.ext.rextSocialNetwork.data.activeTwitter) && $res.ext.rextSocialNetwork.data.activeTwitter}
                <div class="share-net twitter">
                  <a class="icon-share twitter" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}&text={$res.ext.rextSocialNetwork.data["textTwitter"]}">
                    <i class="fa fa-twitter-square"></i>
                  </a>
                </div>
              {/if}
            </div>
          {/if}
        </li>
        <li class="elementFav">
          {if isset($rextFavouriteBlock)}{$rextFavouriteBlock}{/if}
        </li>
      </ul>
    </div>

    <div class="mediumDescription">
      {if $res.data.mediumDescription}
        {$res.data.mediumDescription|escape:'htmlall'}
      {else}
        {$res.data.shortDescription|escape:'htmlall'}
      {/if}
    </div>
  </section>

  {if isset($multimediaGalleries)}
    <section class="multimediaSec container gzSection">
      {$multimediaGalleries}
    </section>
  {/if}

  <section class="contentSec container gzSection">
    <div class="content">
      {$res.data.content}
    </div>
  </section>

  <section class="locationSec gzSection">
    <div class="locationLight">
      <div class="location container">
        <div class="title">
          {t}Location{/t}
        </div>
        {if (isset($rextContactBlock) && $rextContactBlock!="")}
          <div class="{$res.data.rTypeIdName} accommodation">
            {$rextContactBlock}
          </div>
        {/if}
      </div>
    </div>

    <div class="locationDark">
      {if (isset($res.ext.rextContact.data.directions) && $res.ext.rextContact.data.directions!== "")
      || ( isset( $res.ext.rextMapDirections.data ) && $res.ext.rextMapDirections.data !=="")}
        <div class="directions">
          <div class="container">
            <div class="title">
              {t}See indications{/t} <i class="fa fa-sort-down"></i><i class="fa fa-sort-up" style="display:none;"></i>
            </div>
            {if (isset($res.ext.rextContact.data.directions) && $res.ext.rextContact.data.directions!== "")}
              <div class="indications row" style="display:none;">
                <div class="col-md-12">
                  {$res.ext.rextContact.data.directions|escape:'htmlall'}
                </div>
              </div>
            {/if}
          </div>
        </div>
        {if isset( $res.ext.rextMapDirections.data ) && $res.ext.rextMapDirections.data}
          {$rextMapDirectionsBlock}
        {/if}
      {/if}
      {$rextMapBlock}
    </div>
    {if isset($collections) || isset($rextRoutesBlock)}
      <div class="grey-bar"></div>
    {/if}
  </section>

  <section class="routesSec gzSection">
    {if isset($rextRoutesBlock)}
      {$rextRoutesBlock}
    {/if}
  </section>

  {if isset($collections)}
    <section class="collectionSec container gzSection">
      {$collections}
    </section>
  {/if}

  {if isset($rextCommentBlock)}
    <section class="commentSec container gzSection">
      {$rextCommentBlock}
    </section>
  {/if}

</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeEspazoNatural module -->
