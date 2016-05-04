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


<!-- rTypeViewBlock.tpl en rTypeAppHotel module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}" data-resource="{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Aloxamentos con encanto" src="{$cogumelo.publicConf.media}/img/aloxamentosIcon.png"></img>
          <h1>{$res.data.title}</h1>
        </div>
        <div class="stars hidden-xs col-sm-3 col-md-2">
          <i class="fa fa-star-o"></i>
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
      {if isset($res.ext.rextAccommodation.data.averagePrice) && $res.ext.rextAccommodation.data.averagePrice}
      <div class="reservationBox">
        <div class="priceText">{t}Average night rate{/t}</div>
        <div class="priceAmount"><span class="num">{$res.ext.rextAccommodation.data.averagePrice|escape:'htmlall'}</span><span class="unit"> €</span></div>
        {if $res.ext.rextAccommodation.data.reservationURL || $res.ext.rextAccommodation.data.reservationPhone}
          <div class="reservationBtb">
            {if $res.ext.rextAccommodation.data.reservationURL}
              <a href="{$res.ext.rextAccommodation.data.reservationURL}" target="blank">{t}Reserve{/t}</a>
            {elseif $res.ext.rextAccommodation.data.reservationPhone}
              <div class="showReservation">{t}Reserve{/t}</div>
            {/if}
          </div>
        {/if}
        </div>
        <div class="reservationData" style="display:none;">
          <div class="priceText">{t}Reservation phone{/t}</div>
          <div class="priceAmount"><span class="num">{$res.ext.rextAccommodation.data.reservationPhone|escape:'htmlall'}</span></div>
          <div class="reservationBtb">
              <div class="showAverageRate">{t}Show average rate{/t}</div>
          </div>
        </div>
      {/if}
    </div>
  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar row">
      <ul class="type col-xs-6 col-sm-6 col-md-6 clearfix">
        {if isset($res.data.accommodationType)}
        {foreach from=$res.data.accommodationType item=termInfo}
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
                  <a class="icon-share twitter" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}&text={$res.ext.rextSocialNetwork.data["textTwitter"]}">
                    <i class="fa fa-twitter-square"></i>
                  </a>
                </div>
              {/if}
            </div>
          {/if}
        </li>
        <li class="elementFav">
          <i class="fa fa-heart-o"></i>
          <i class="fa fa-heart"></i>
        </li>
      </ul>
    </div>



    <div class="taxonomyBar row clearfix">

      {if isset($res.data.rTypeIdName) && $res.data.rTypeIdName == 'rtypeAppHotel'}
      <div class="taxStars col-xs-12 col-sm-2">
        {if isset($res.data.accommodationCategory)}
          <div class="taxStarsBox">
          {foreach from=$res.data.accommodationCategory item=termInfo}
            {for $foo=1 to $termInfo.idName|substr:0:1}
              <i class="fa fa-star"></i>
            {/for}
          </div>
        {/foreach}
        {/if}
      </div>
      {/if}

      <div class="taxIcons col-xs-12 col-sm-10">
        {foreach $allServices as $termId => $term}

            <div class="icon clearfix {if !is_array($res.data.accommodationServices) || !$termId|array_key_exists:$res.data.accommodationServices}light{/if}">
              <img alt="{$term.name}" title="{$term.name}" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$term.icon}/typeIconCategory/{$term.icon}.png" />
            </div>
        {/foreach}

        {foreach $allFacilities as $termId => $term}
            <div class="icon clearfix {if !is_array($res.data.accommodationServices) || !$termId|array_key_exists:$res.data.accommodationFacilities}light{/if}">
              <img alt="{$term.name}" title="{$term.name}" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$term.icon}/typeIconCategory/{$term.icon}.png" />
            </div>
        {/foreach}
      </div>
    </div>

    <div class="mediumDescription">
      {$res.data.mediumDescription|escape:'htmlall'}
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
    </div>



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

  {if isset($rextCommentBlock)}
    <section class="commentSec container gzSection">
      {$rextCommentBlock}
    </section>
  {/if}
</div>
