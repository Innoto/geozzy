<!-- rTypeViewBlock.tpl en rtypeUrl module -->

<div class="resViewBlock rtypeUrl">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="title">
    <h3>{$res.data.title|escape:'htmlall'}</h3>
  </div>

  {if isset($res.data.shortDescription) && $res.data.shortDescription ne ''}
  <div class="shortDescription">
    <p>{$res.data.shortDescription|escape:'htmlall'}</p>
  </div>
  {/if}

  {if isset($res.data.image)}
  <div class="image cgmMForm-fileField">
    <style type="text/css">.cgmMForm-fileField img { height: 100px }</style>
    <img src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}" {if isset( $res.data.image.title )}alt="{$res.data.image.title}" title="{$res.data.image.title}"{/if}></img>
  </div>
  {/if}

  <div class="rExtViewBlock rTypeUrl">
    <div class="rTypeUrl rExtUrl">
      {$rextUrlBlock}
    </div>
  </div>

</div>

<!-- /rTypeViewBlock.tpl en rtypeUrl module -->
