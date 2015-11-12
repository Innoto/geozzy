{block name="headCssIncludes" append}
<style type="text/css">
  .imageSec{
    background: rgba(0, 0, 0, 0) url("/cgmlImg/{$image.id}/fast/{$image.id}.jpg") no-repeat scroll center center / cover;
    height: 50vh;
  }
  @media screen and (min-width: 1200px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("/cgmlImg/{$image.id}/resourceLg/{$image.id}.jpg") no-repeat scroll center center / cover;
    }
  } /*1200px*/

  @media screen and (max-width: 1199px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("/cgmlImg/{$image.id}/resourceMd/{$image.id}.jpg") no-repeat scroll center center / cover;
    }
  }/*1199px*/

  @media screen and (max-width: 991px) {
    .resource .imageSec {
      background: rgba(0, 0, 0, 0) url("/cgmlImg/{$image.id}/resourceSm/{$image.id}.jpg") no-repeat scroll center center / cover;
    }
  }/*991px*/
</style>
{/block}

<!-- rTypeViewBlock.tpl en rTypeHotel module -->

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="imageSec gzSection">
  <!--   {if isset( $image )}
      <img src="/cgmlformfilews/{$image.id}"
        {if isset( $image.title )}alt="{$image.title}" title="{$image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if} -->
    {if $rExtAccommodation_averagePrice}
    <div class="reservationSec container">
      <div class="reservationBox">
        <div class="priceText">{t}Precio medio por noche{/t}</div>
        <div class="priceAmount"><span class="num">{$rExtAccommodation_averagePrice|escape:'htmlall'}</span><span class="unit"> €</span></div>
        <div class="reservationBtb">
          {if $rExtAccommodation_reservationURL}
          <a href="{$rExtAccommodation_reservationURL}" target="blank">{t}Reservar{/t}</a>
          {elseif $rExtAccommodation_reservationPhone}
          <div class="showReservation">{t}Reservar{/t}</div>
          {/if}
        </div>
      </div>
      <div class="reservationData" style="display:none;">
        <div class="priceText">{t}Teléfono para reservas{/t}</div>
        <div class="priceAmount"><span class="num">{$rExtAccommodation_reservationPhone|escape:'htmlall'}</span></div>
      </div>
    </div>
    {/if}
  </div>

  <div class="contentSec container gzSection">
    <div class="typeBar">
      <div class="type col-lg-10">{$rTypeIdName|escape:'htmlall'}</div>
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
      {$shortDescription|escape:'htmlall'}
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
      {$content}
    </div>
  </div>

  <div class="locationSec gzSection">
    <div class="location container">
      <div class="title">
        {t}Contact{/t}
      </div>
      <div class="{$rTypeIdName|escape:'htmlall'} accommodation">
        {$rextContact}
      </div>
    </div>

    <div class="directions">
      <div class="container">
        <div class="title">
          {t}See indications{/t} <i class="fa fa-sort-desc"></i>
        </div>
        <div class="indications row" style="display:none;">
          <div class="col-lg-8">
            {$rExtContact_directions|escape:'htmlall'}
          </div>
          <div class="col-lg-4">
            <div class="search">
              {t}How to arrive from?{/t} <i class="fa fa-search"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    {if isset( $loc )}
    <div class="map">
      <div class="container">
        (AQUI VAI O MAPA)
        {$loc|escape:'htmlall'}
        {$defaultZoom|escape:'htmlall'}
      </div>
    </div>
    {/if}
  </div>

  <div class="gallerySec container gzSection">
    <!-- Galería Multimedia -->
    <div class="imageGallery">
      <label for="imgResource" class="cgmMForm"></label>
      {if isset( $image )}
        <style type="text/css">.cgmMForm-fileField img { height: 100px; }</style>
        <img src="/cgmlformfilews/{$image.id}"
          {if isset( $image.title )}alt="{$image.title}" title="{$image.title}"{/if}></img>
      {else}
        <p>{t}None{/t}</p>
      {/if}
    </div>
  </div>

  <div class="reservationSec container gzSection">
    <div class="{$rTypeIdName|escape:'htmlall'}">
      <p> --- {$rTypeIdName|escape:'htmlall'} Ext RESERVAS --- </p>
      <div class="{$rTypeIdName|escape:'htmlall'} accommodation">
        {$rextAccommodation}
      </div>
    </div>
  </div>

  <div class="collectionSec container gzSection">
    {if isset($collections)}
    <div class="collections">
      <label for="collections" class="cgmMForm">{t}Collections{/t}</label>
      {$collections}
    </div>
    {/if}
 </div>



<!-- /rTypeViewBlock.tpl en rTypeHotel module -->
