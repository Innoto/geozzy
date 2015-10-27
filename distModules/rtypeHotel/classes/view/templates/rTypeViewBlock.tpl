<!-- rTypeViewBlock.tpl en rTypeHotel module -->

<p> --- rTypeViewBlock.tpl en rTypeHotel module --- </p>



  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="row image">
    {if isset( $image )}
      <img src="/cgmlformfilews/{$image.id}"
        {if isset( $image.title )}alt="{$image.title}" title="{$image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if}
  </div>

<div class="row resViewBlock">
  <h3>
    <div class="shortDescription">
      {$shortDescription|escape:'htmlall'}
    </div>
  </h3>

  <p>--- Galería multimedia ---</p>

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
      <img src="/cgmlformfilews/{$image.id}"
        {if isset( $image.title )}alt="{$image.title}" title="{$image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if}
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

</div>

<!-- /rTypeViewBlock.tpl en rTypeHotel module -->
