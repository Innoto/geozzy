{block name="headCssIncludes" append}
<style type="text/css">
  .imageSec{
    height:420px;
    background: rgba(0, 0, 0, 0) url("/cgmlImg/{$image.id}/fast/{$image.id}.jpg") no-repeat scroll center center / cover;
  }
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
  </div>

  <div class="contentSec container gzSection">
    <div class="typeBar">
      <div class="type col-lg-10">{$rType|escape:'htmlall'}</div>
      <div class="social col-lg-2 row">
        <div class="col-lg-6">
          MY
        </div>
        <div class="col-lg-6">
          SOCIAL
        </div>
      </div>
    </div>

    <div class="shortDescription row">
      {$shortDescription|escape:'htmlall'}
    </div>

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

    <div class="mediumDescription">
      {$content}
    </div>
  </div>

  <div class="locationSec container gzSection">
    <div class="location">
      LOCATION
    </div>
  </div>



  {if isset($collections)}
  <div class="collections">
    <label for="collections" class="cgmMForm">{t}Collections{/t}</label>
    {$collections}
  </div>
  {/if}

  <div class="rTypeHotel">
    <p> --- rTypeHotel Ext --- </p>
    <div class="rTypeHotel accommodation">
      {$rextAccommodation}
    </div>
    <div class="rTypeHotel accommodation">
      {$rextContact}
    </div>
  </div>


<!-- /rTypeViewBlock.tpl en rTypeHotel module -->
