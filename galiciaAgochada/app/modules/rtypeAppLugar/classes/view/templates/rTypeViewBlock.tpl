{block name="headCssIncludes" append}

<!-- rTypeViewBlock.tpl en rTypeLugar module -->

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


<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}" data-resource="{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Rincóns con estilo" src="{$cogumelo.publicConf.media}/img/rinconsIcon.png"></img>
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
        <p>{t}volver{/t}</p>
      </a>

      {if (isset($rextAudioguideBlock) && $rextAudioguideBlock!="")}
      <div class="resumeBox">
        <div class="playAudio">
            {$rextAudioguideBlock}
        </div>
      </div>
      {/if}

    </div>
  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar row">

      <ul class="type col-xs-6 col-sm-6 col-md-6 clearfix">
        {if isset($res.data.rextAppLugarType)}
        {foreach from=$res.data.rextAppLugarType item=termInfo}
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
                  <a class="icon-share twitter" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}&text={$res.ext.rextSocialNetwork.data["textTwitter"]} vía{$cogumelo.publicConf.site_host}">
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
    {if (isset($rextContactBlock) && $rextContactBlock!="")}
    <div class="locationLight">
      <div class="location container">
        <div class="title">
          {t}Location{/t}
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

    {if isset($collections)}
      <div class="grey-bar"></div>
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
<!-- /rTypeViewBlock.tpl en rTypeLugar module -->
