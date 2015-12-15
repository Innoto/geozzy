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

<!-- rTypeViewBlock.tpl en rTypeRestaurant module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-lg-10">
          <img class="img-responsive" alt="Aloxamentos con encanto" src="/media/img/xantaresIcon.png"></img>
          <h1>{$res.data.title}</h1>
        </div>
        <div class="stars col-lg-2">
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
    {if $res.ext.rextEatAndDrink.data.averagePrice}
    <div class="reservationSec container">
      <div class="reservationBox">
        <div class="priceText">{t}Average night prize{/t}</div>
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
      <ul class="type col-lg-10">
        {foreach from=$res.data.eatanddrinkType item=termInfo}
          <li>
            <img width="16" src="/cgmlImg/{$termInfo.icon}/typeIconMini/{$termInfo.icon}.svg" />
            <div class="name">{$termInfo["name_$GLOBAL_C_LANG"]}</div>
          </li>
          {break}
        {/foreach}
      </ul>
      {/if}
      <ul class="social col-lg-2">
        <li class="elementShare">
          <i class="fa fa-share-alt"></i>
        </li>
        <li class="elementFav">
          <i class="fa fa-heart-o"></i>
          <i class="fa fa-heart"></i>
        </li>
      </ul>
    </div>

    <div class="shortDescription">
      {$res.data.shortDescription|escape:'htmlall'}
    </div>

    <div class="mediumDescription">
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
  </section>

{if isset($imggallery)}
  <section class="gallerySec container gzSection">

    <div id="imageGallery" style="display:none;">
      {$imggallery}
    </div>
  </section>
{/if}

{if isset($collections)}
  <section class="collectionSec container gzSection">
    <h4>{t}Related resources{/t}</h4>
    {$collections}
    <div id="collectionsAllGallery" style="display:none;">
    </div>
    <div class="more">{t}Ver más...{/t}</div>
    <div class="less" style="display:none;">{t}Ver menos{/t}</div>
  </section>
{/if}

</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeRestaurant module -->
