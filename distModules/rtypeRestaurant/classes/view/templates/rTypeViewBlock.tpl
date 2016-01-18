{block name="headCssIncludes" append}
<style type="text/css">
  .imageSec{
    background: rgba(0, 0, 0, 0) url("/cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    height: 50vh;
  }
  @media screen and (min-width: 1200px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("/cgmlImg/{$res.data.image.id}/resourceLg/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    }
  } /*1200px*/

  @media screen and (max-width: 1199px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("/cgmlImg/{$res.data.image.id}/resourceMd/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    }
  }/*1199px*/

  @media screen and (max-width: 991px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("/cgmlImg/{$res.data.image.id}/resourceSm/{$res.data.image.id}.jpg") no-repeat scroll center center / cover;
    }
  }/*991px*/
</style>
{/block}

<!--
{block name="socialMeta" append}
  <meta property="og:url" content="{$site_host}{$res.data["urlAlias_$GLOBAL_C_LANG"]}" />
  <meta property="og:image" content="/cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg" />
  <meta property="og:description" content="{$res.ext.rextSocialNetwork.data["textFb_$GLOBAL_C_LANG"]}" />
  <meta name="description" content="{$res.ext.rextSocialNetwork.data["textFb_$GLOBAL_C_LANG"]}" />
{/block}
-->


<!-- rTypeViewBlock.tpl en rTypeRestaurant module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-xs-12 col-sm-9 col-md-10">
          <img class="iconTitleBar img-responsive" alt="Aloxamentos con encanto" src="/media/img/xantaresIcon.png"></img>
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
  <!--   {if isset( $res.data.image )}
      <img src="/cgmlImg/{$res.data.image.id}"
        {if isset( $res.data.image.title )}alt="{$res.data.image.title}" title="{$res.data.image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if} -->
    {if isset($res.ext.rextEatAndDrink.data.averagePrice) && $res.ext.rextEatAndDrink.data.averagePrice}
    <div class="reservationSec container">
      <div class="reservationBox">
        <div class="priceText">{t}Average night price{/t}</div>
        <div class="priceAmount"><span class="num">{$res.ext.rextEatAndDrink.data.averagePrice|escape:'htmlall'}</span><span class="unit"> €</span></div>
        <div class="reservationBtb">
          {if $res.ext.rextEatAndDrink.data.reservationURL}
          <a href="{$res.ext.rextEatAndDrink.data.reservationURL}" target="blank">{t}Reserve{/t}</a>
          {elseif $res.ext.rextEatAndDrink.data.reservationPhone}
          <div class="showReservation">{t}Reserve{/t}</div>
          {/if}
        </div>
      </div>
      <div class="reservationData" style="display:none;">
        <div class="priceText">{t}Teléfono para reservas{/t}</div>
        <div class="priceAmount"><span class="num">{$res.ext.rextEatAndDrink.data.reservationPhone|escape:'htmlall'}</span></div>
      </div>
    </div>
    {/if}
  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar row">
      {if isset($res.data.eatanddrinkType)}
      <ul class="type type col-xs-6 col-sm-6 col-md-6 clearfix">
        {foreach from=$res.data.eatanddrinkType item=termInfo}
          <li>
            <img src="/cgmlImg/{$termInfo.icon}/typeIconMini/{$termInfo.icon}.svg" />
            <div class="name">{$termInfo["name_$GLOBAL_C_LANG"]}</div>
          </li>
          {break}
        {/foreach}
      </ul>
      {/if}
      <ul class="social col-xs-6 col-sm-6 col-md-6 clearfix">
        <li class="elementShare">
          {if isset($res.ext.rextSocialNetwork) && (isset($res.ext.rextSocialNetwork.data.activeFb) || isset($res.ext.rextSocialNetwork.data.activeTwitter))}
            <div class="share"><i class="fa fa-share-alt"></i></div>
            <div class="share-open" style="display:none;">
              {if isset($res.ext.rextSocialNetwork.data.activeFb) && $res.ext.rextSocialNetwork.data.activeFb}
                <div class="share-net fb">
                  <a class="icon-share facebook" target="_blank" rel="nofollow" href="http://www.facebook.com/sharer.php?u=http://139.162.192.185/{$res.data["urlAlias_$GLOBAL_C_LANG"]}&t={$res.ext.rextSocialNetwork.data["textFb_$GLOBAL_C_LANG"]}">
                      <i class="fa fa-facebook-square"></i>
                  </a>
                </div>
              {/if}
              {if isset($res.ext.rextSocialNetwork.data.activeTwitter) && $res.ext.rextSocialNetwork.data.activeTwitter}
                <div class="share-net twitter">
                  <a class="icon-share twitter" target="_blank" rel="nofollow" href="http://twitter.com/share?url={$site_host}{$res.data["urlAlias_$GLOBAL_C_LANG"]}&text={$res.ext.rextSocialNetwork.data["textTwitter_$GLOBAL_C_LANG"]} {$site_host}{$res.data["urlAlias_$GLOBAL_C_LANG"]}">
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

    <div class="mediumDescription">
      {$res.data.mediumDescription|escape:'htmlall'}
    </div>

    <div class="content">
      {$res.data.content}
    </div>
  </section>

  <section class="locationSec gzSection">
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

    <div class="locationDark">
      {if isset($res.ext.rextContact.data.directions) && $res.ext.rextContact.data.directions!== ""}
      <div class="directions">
        <div class="container">
          <div class="title">
            {t}See indications{/t} <i class="fa fa-sort-desc"></i>
          </div>
          {if isset( $res.data.loc )}
          <div class="indications row" style="display:none;">
            <div class="col-lg-8">
              {$res.ext.rextContact.data.directions|escape:'htmlall'}
            </div>
            <div class="col-lg-4">
              <div class="search">
                {t}How to arrive from?{/t} <i class="fa fa-search"></i>
              </div>
            </div>
          </div>
          {else}
          <div class="indications" style="display:none;">
              {$res.ext.rextContact.data.directions|escape:'htmlall'}
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
<!-- /rTypeViewBlock.tpl en rTypeRestaurant module -->
