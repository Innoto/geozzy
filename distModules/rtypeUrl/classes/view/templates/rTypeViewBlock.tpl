<!-- rTypeViewBlock.tpl en rtypeUrl module -->

<p> --- rTypeViewBlock.tpl en rtypeUrl module --- </p>

<div class="resViewBlock rtypeUrl">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="title">
    <label class="cgmMForm">{t}Title{/t}</label>
    {$res.data.title|escape:'htmlall'}
  </div>

  <div class="shortDescription">
    <label class="cgmMForm">{t}Short description{/t}</label>
    {$res.data.shortDescription|escape:'htmlall'}
  </div>

  <div class="image cgmMForm-fileField">
    <label for="imgResource" class="cgmMForm">{t}Image{/t}</label>
    {if isset( $res.data.image )}
      <style type="text/css">.cgmMForm-fileField img { height: 100px }</style>
      <img src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}"
        {if isset( $res.data.image.title )}alt="{$res.data.image.title}" title="{$res.data.image.title}"{/if}></img>
    {else}
      <p>{t}None{/t}</p>
    {/if}
  </div>

  <div class="rExtViewBlock rTypeUrl">
    <p> --- rTypeUrl Ext --- </p>
    <div class="rTypeUrl rExtUrl">
      {$rextUrlBlock}
    </div>
  </div>

</div>

<!-- /rTypeViewBlock.tpl en rtypeUrl module -->
