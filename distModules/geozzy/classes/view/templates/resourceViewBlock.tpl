<!-- resourceViewBlock.tpl en geozzy module -->

<p> --- resourceViewBlock.tpl en geozzy module --- </p>

<div class="resViewBlock">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <!-- <div class="title">
    <label class="cgmMForm">{t}Title{/t}</label>
    {$title|escape:'htmlall'}
  </div>
  <div class="shortDescription">
    <label class="cgmMForm">{t}Short description{/t}</label>
    {$shortDescription|escape:'htmlall'}
  </div> -->

  <div class="titleSec gzSection">
    <div class="container">
      <div class="title col-lg-10">
        <img alt="topicIcon" src="/mediaCache/module/geozzy/img/topic/{$topic}.png"/>
        <h1>{$title|escape:'htmlall'}</h1>
      </div>
      <div class="stars col-lg-2">
        <div class="col-lg-2">
          <i class="far fa-star"></i>
        </div>
        <div class="col-lg-2">
          <i class="far fa-star"></i>
        </div>
        <div class="col-lg-2">
          <i class="far fa-star"></i>
        </div>
        <div class="col-lg-2">
          <i class="far fa-star"></i>
        </div>
        <div class="col-lg-2">
          <i class="far fa-star"></i>
        </div>
      </div>
    </div>
  </div>

  <h3>
    <div class="shortDescription">
      {$shortDescription|escape:'htmlall'}
    </div>
  </h3>

  <div class="mediumDescription">
    <label for="mediumDescription" class="cgmMForm">{t}Medium description{/t}</label>
    {$mediumDescription}
  </div>

  <div class="content">
    <label for="content" class="cgmMForm">{t}Content{/t}</label>
    {$content}
  </div>

  <div class="image cgmMForm-fileField">
    <label for="imgResource" class="cgmMForm">{t}Image{/t}</label>
    {if isset( $image )}
      <style type="text/css">.cgmMForm-fileField img { height: 100px }</style>
      <img src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$image.id}"
        {if isset( $image.title )}alt="{$image.title}" title="{$image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if}
  </div>

  {if isset($externalUrl)}
  <div class="externalUrl">
    <label for="externalUrl" class="cgmMForm">{t}External Url{/t}</label>
    {$externalUrl|escape:'htmlall'}
  </div>
  {/if}

  {if isset($collections)}
  <div class="collections">
    <label for="collections" class="cgmMForm">{t}Collections{/t}</label>
    {$collections}
  </div>
  {/if}


</div>

<!-- /resourceViewBlock.tpl en geozzy module -->
