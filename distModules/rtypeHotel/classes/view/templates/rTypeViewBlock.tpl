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

<script type='text/javascript' src='unitegallery/themes/default/ug-theme-default.js'></script>
<link rel='stylesheet' href='unitegallery/themes/default/ug-theme-default.css' type='text/css' />

<!-- rTypeViewBlock.tpl en rTypeHotel module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-lg-10">
          <img class="img-responsive" alt="Aloxamentos con encanto" src="/media/img/aloxamentosIcon.png"></img>
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
    {if $res.ext.rextAccommodation.data.averagePrice}
    <div class="reservationSec container">
      <div class="reservationBox">
        <div class="priceText">{t}Average night rate{/t}</div>
        <div class="priceAmount"><span class="num">{$res.ext.rextAccommodation.data.averagePrice|escape:'htmlall'}</span><span class="unit"> €</span></div>
        {if $res.ext.rextAccommodation.data.reservationURL || $res.ext.rextAccommodation.data.reservationPhone}
          <div class="reservationBtb">
            {if $res.ext.rextAccommodation.data.reservationURL}
              <a href="{$res.ext.rextAccommodation.data.reservationURL}" target="blank">{t}Reservar{/t}</a>
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
    </div>
    {/if}
  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar row">
      {if isset($res.data.accommodationType)}
      <ul class="type col-lg-10">
        {foreach from=$res.data.accommodationType item=termInfo}
          <li>
            {if isset($termInfo.icon)}<img width="32" src="/cgmlImg/{$termInfo.icon}/typeIconMini/{$termInfo.icon}.svg" />{/if}
            <div class="name">{$termInfo.name_es}</div>
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

    <div class="taxonomyBar row">
      <div class="taxStars col-lg-2">
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
      <div class="taxIcons col-lg-10">
        {if isset($res.data.accommodationServices)}
          {foreach from=$res.data.accommodationServices item=termInfo}
            <div class="icon">
              {if isset($termInfo.icon)}<img width="32" src="/cgmlImg/{$termInfo.icon}/typeIconMini/{$termInfo.icon}.svg" />{else}{$termInfo.name_es}{/if}
            </div>
          {/foreach}
        {/if}
        {if isset($res.data.accommodationFacilities)}
          {foreach from=$res.data.accommodationFacilities item=termInfo}
            <div class="icon">
              {if isset($termInfo.icon)}<img width="32" src="/cgmlImg/{$termInfo.icon}/typeIconMini/{$termInfo.icon}.svg" />{else}{$termInfo.name_es}{/if}
            </div>
          {/foreach}
        {/if}
      </div>
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

  <!-- Galería Multimedia
  <section class="gallerySec container gzSection">

    <div id="imageGallery" style="display:none;">

        <img alt="Image 1 Title" src="/cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg"
          data-image="/cgmlImg/{$res.data.image.id}/resourceSm/{$res.data.image.id}.jpg"
          data-description="Image 1 Description">

        <img alt="Image 2 Title" src="http://lorempixel.com/400/200/nature/1"
          data-image="http://lorempixel.com/400/200/nature/1"
          data-description="Image 2 Description">

        <img alt="Image 3 Title" src="http://lorempixel.com/400/200/nature/2"
          data-image="http://lorempixel.com/400/200/nature/2"
          data-description="Image 3 Description">

        <img alt="Image 4 Title" src="http://lorempixel.com/400/100/"
          data-image="http://lorempixel.com/400/100/"
          data-description="Image 4 Description">

        <img alt="Image 5 Title" src="http://lorempixel.com/150/350/"
          data-image="http://lorempixel.com/150/350/"
          data-description="Image 5 Description">

        <img alt="Image 6 Title" src="http://lorempixel.com/400/200/nature/3"
            data-image="http://lorempixel.com/400/200/nature/3"
            data-description="Image 3 Description">

        <img alt="Image 7 Title" src="http://lorempixel.com/400/300/"
            data-image="http://lorempixel.com/400/300/"
            data-description="Image 4 Description">

        <img alt="Image 8 Title" src="http://lorempixel.com/250/450/"
            data-image="http://lorempixel.com/400/200/"
            data-description="Image 5 Description">

    </div>
  </section>-->

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

  <!--
    <div class="reservationSec container gzSection">
      <div class="{$res.data.rTypeIdName}">
        <p> {$res.data.rTypeIdName} Ext RESERVAS </p>
        <div class="{$res.data.rTypeIdName} accommodation">
          {$rextAccommodationBlock}
        </div>
      </div>
    </div>
  -->

</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeHotel module -->
