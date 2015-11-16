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

<!-- rTypeViewBlock.tpl en rTypeHotel module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="imageSec gzSection">
  <!--   {if isset( $res.data.image )}
      <img src="/cgmlformfilews/{$res.data.image.id}"
        {if isset( $res.data.image.title )}alt="{$res.data.image.title}" title="{$res.data.image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if} -->
    {if $res.ext.rextAccommodation.data.averagePrice}
    <div class="reservationSec container">
      <div class="reservationBox">
        <div class="priceText">{t}Precio medio por noche{/t}</div>
        <div class="priceAmount"><span class="num">{$res.ext.rextAccommodation.data.averagePrice|escape:'htmlall'}</span><span class="unit"> €</span></div>
        <div class="reservationBtb">
          {if $res.ext.rextAccommodation.data.reservationURL}
          <a href="{$res.ext.rextAccommodation.data.reservationURL}" target="blank">{t}Reservar{/t}</a>
          {elseif $res.ext.rextAccommodation.data.reservationPhone}
          <div class="showReservation">{t}Reservar{/t}</div>
          {/if}
        </div>
      </div>
      <div class="reservationData" style="display:none;">
        <div class="priceText">{t}Teléfono para reservas{/t}</div>
        <div class="priceAmount"><span class="num">{$res.ext.rextAccommodation.data.reservationPhone|escape:'htmlall'}</span></div>
      </div>
    </div>
    {/if}
  </div>

  <div class="contentSec container gzSection">
    <div class="typeBar">
      <div class="type col-lg-10">{$res.data.rTypeIdName}</div>
      <div class="social col-lg-2 row">
        <div class="col-lg-6">
          MY
        </div>
        <div class="col-lg-6">
          SOCIAL
        </div>
      </div>
    </div>

    <div class="shortDescription">
      {$res.data.shortDescription|escape:'htmlall'}
    </div>

    <div class="taxonomyBar row">
      <div class="taxStars col-lg-2">
        VALORACIONES
      </div>
      <div class="taxIcons col-lg-10">
        <div class="icon">
          icono 1
        </div>
        <div class="icon">
          icono 2
        </div>
      </div>
    </div>

    <div class="mediumDescription">
      {$res.data.content}
    </div>
  </div>

  <div class="locationSec gzSection">
    <div class="location container">
      <div class="title">
        {t}Contact{/t}
      </div>
      <div class="{$res.data.rTypeIdName} accommodation">
        {$rextContactBlock}
      </div>
    </div>

    <div class="directions">
      <div class="container">
        <div class="title">
          {t}See indications{/t} <i class="fa fa-sort-desc"></i>
        </div>
        <div class="indications row" style="display:none;">
          <div class="col-lg-8">
            {$res.ext.rextContact.directions|escape:'htmlall'}
          </div>
          <div class="col-lg-4">
            <div class="search">
              {t}How to arrive from?{/t} <i class="fa fa-search"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {if isset( $res.data.loc )}
    <div class="map">
      <div class="container">
        (AQUI VAI O MAPA)
        {$res.data.loc|escape:'htmlall'}
        {$res.data.defaultZoom|escape:'htmlall'}
      </div>
    </div>
    {/if}
  </div>

  <div class="gallerySec container gzSection">
    <!-- Galería Multimedia -->
    <div class="imageGallery">
      <label for="imgResource" class="cgmMForm"></label>
      {if isset( $res.data.image )}
        <style type="text/css">.cgmMForm-fileField img { height: 100px; }</style>
        <img src="/cgmlformfilews/{$res.data.image.id}"
          {if isset( $res.data.image.title )}alt="{$res.data.image.title}" title="{$res.data.image.title}"{/if}></img>
      {else}
        <p>{t}None{/t}</p>
      {/if}
    </div>
  </div>

  <div class="reservationSec container gzSection">
    <div class="{$res.data.rTypeIdName}">
      <p> --- {$res.data.rTypeIdName} Ext RESERVAS --- </p>
      <div class="{$res.data.rTypeIdName} accommodation">
        {$rextAccommodationBlock}
      </div>
    </div>
  </div>

  <div class="collectionSec container gzSection">
    {if isset($res.data.collections)}
    <div class="collections">
      <label for="collections" class="cgmMForm">{t}Collections{/t}</label>
      {$res.data.collections}
    </div>
    {/if}
  </div>

</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeHotel module -->
