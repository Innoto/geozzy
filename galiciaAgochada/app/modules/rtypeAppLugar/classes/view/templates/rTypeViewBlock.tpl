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

<!-- rTypeViewBlock.tpl en rTypeLugar module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="titleBar">
    <div class="container">
      <div class="row">
        <div class="col-lg-10">
          <img class="img-responsive" alt="Rincóns con estilo" src="/media/img/rinconsIcon.png"></img>
          <h1>{$res.data.title}</h1>
        </div>
        <div class="stars col-lg-2">
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
          <i class="fa selected fa-star-o"></i>
        </div>
      </div>
    </div>
  </div>

  <section class="imageSec gzSection">

  </section>

  <section class="contentSec container gzSection">
    <div class="typeBar row">
      {if isset($res.data.rextAppLugarType)}
      <ul class="type col-lg-10">
        {foreach from=$res.data.rextAppLugarType item=termInfo}
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

    <div class="mediumDescription">
      {$res.data.content}
    </div>
  </section>

  <section class="locationSec gzSection">
    {if isset($rextContactBlock)}
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

  <section class="gallerySec container gzSection">
    <!-- Galería Multimedia -->
    <div class="imageGallery">
      <label for="imgResource" class="cgmMForm"></label>
      {if isset( $res.data.image )}
        <style type="text/css">.cgmMForm-fileField img { height: 100px; }</style>
        <img src="/cgmlImg/{$res.data.image.id}"
          {if isset( $res.data.image.title )}alt="{$res.data.image.title}" title="{$res.data.image.title}"{/if}></img>
      {else}
        <p>{t}None{/t}</p>
      {/if}
    </div>
  </section>

<!--
  <div class="reservationSec container gzSection">
    <div class="{$res.data.rTypeIdName}">
      <p>  {$res.data.rTypeIdName} Ext RESERVAS  </p>
      <div class="{$res.data.rTypeIdName} eatanddrink">
      </div>
    </div>
  </div>
-->

  <section class="collectionSec container gzSection">
    {if isset($res.data.collections)}
    <div class="collections">
      <label for="collections" class="cgmMForm">{t}Collections{/t}</label>
      {$res.data.collections}
    </div>
    {/if}
  </section>

</div><!-- /.resource .resViewBlock -->
<!-- /rTypeViewBlock.tpl en rTypeRestaurant module -->
