<!-- rExtViewBlock.tpl en rExtUrl module -->

<p> --- rExtViewBlock.tpl en rExtUrl module</p>

<div class="rExtUrl">

  <div class="url">
    <label>{t}External URL{/t}</label>
    {$rExt.data.externalUrl|escape:'htmlall'}
  </div>

  <div class="urlContentType">
    <label>{t}URL content type{/t}</label>
    {$rExt.data.urlContentType|escape:'htmlall'}
  </div>

  <div class="embed">
    <label>{t}Embed HTML{/t}</label>
    {$rExt.data.embed}
  </div>

  <div class="author">
    <label>{t}Author{/t}</label>
    {$rExt.data.author|escape:'htmlall'}
  </div>

</div>

<!-- /rExtViewBlock.tpl en rExtUrl module -->


