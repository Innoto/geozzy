<!-- rExtViewBlockImage.tpl en rExtUrl module -->

<p> --- rExtViewBlockImage.tpl en rExtUrl module</p>

<div class="rExtUrl">

  <div class="url">
    <label>{t}External URL{/t}</label>
    {$rExt.data.externalUrl|escape:'htmlall'}
  </div>

  <div class="urlContentType">
    <label>{t}IMAGE{/t}</label>
    <img src="{$externalUrl|escape:'htmlall'}"></img>
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

<!-- /rExtViewBlockImage.tpl en rExtUrl module -->


