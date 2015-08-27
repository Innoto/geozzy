<!-- rExtViewBlock.tpl en rExtUrl module -->

<p> --- rExtViewBlock.tpl en rExtUrl module</p>

<div class="rExtUrl">

  <div class="url">
    <label>{t}External URL{/t}</label>
    {$externalUrl|escape:'htmlall'}
  </div>

  <div class="urlContentType">
    <label>{t}URL content type{/t}</label>
    {$rExtUrl_urlContentType|escape:'htmlall'}
  </div>

  <div class="embed">
    <label>{t}Embed HTML{/t}</label>
    {$rExtUrl_embed}
  </div>

  <div class="author">
    <label>{t}Author{/t}</label>
    {$rExtUrl_author|escape:'htmlall'}
  </div>

</div>

<!-- /rExtViewBlock.tpl en rExtUrl module -->


