<!-- rExtViewBlockPage.tpl en rExtUrl module -->

<p> --- rExtViewBlockPage.tpl en rExtUrl module</p>

<div class="rExtUrl">

  {if isset($rExtUrl_embed) && $rExtUrl_embed ne ''}

    <div class="embed">
      <label>{t}Embed HTML{/t}</label>
      {$rExtUrl_embed}
    </div>

  {else}

    <div class="url">
      <label>{t}External URL{/t}</label>
      {$externalUrl|escape:'htmlall'}
    </div>

    <div class="urlContentType">
      <a href="{$externalUrl|escape:'htmlall'}" target="_blank">{t}Link target _blank{/t}</a>
    </div>

  {/if}

  <div class="author">
    <label>{t}Author{/t}</label>
    {$rExtUrl_author|escape:'htmlall'}
  </div>

</div>

<!-- /rExtViewBlockPage.tpl en rExtUrl module -->


