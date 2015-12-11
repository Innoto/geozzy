<!-- rExtViewBlockPage.tpl en rExtUrl module -->

<p> --- rExtViewBlockPage.tpl en rExtUrl module</p>

<div class="rExtUrl">

  {if isset($rExt.data.embed) && $rExt.data.embed ne ''}

    <div class="embed">
      <label>{t}Embed HTML{/t}</label>
      {$rExt.data.embed}
    </div>

  {else}

    <div class="url">
      <label>{t}External URL{/t}</label>
      {$rExt.data.url|escape:'htmlall'}
    </div>

    <div class="urlContentType">
      <a href="{$url|escape:'htmlall'}" target="_blank">{t}Link target _blank{/t}</a>
    </div>

  {/if}

  <div class="author">
    <label>{t}Author{/t}</label>
    {$rExt.data.author|escape:'htmlall'}
  </div>

</div>

<!-- /rExtViewBlockPage.tpl en rExtUrl module -->
