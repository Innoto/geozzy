<!-- rExtViewAltAppMylandpage.tpl en rExtView module -->

<p> --- rExtViewAltAppMylandpage.tpl en rExtView module --- </p>

<div class="resViewBlock rExtViewAltAppMylandpage">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="altViewInfo">
    <label class="cgmMForm">{t}Alternate view{/t}</label>
    {$res.ext.rextView.data.altViewInfo|escape:'htmlall'}
  </div>

  <div class="title">
    <label class="cgmMForm">{t}Title{/t}</label>
    {$res.data.title|escape:'htmlall'}
  </div>

  <div class="content">
    <label for="content" class="cgmMForm">{t}Content{/t}</label>
    {$res.data.content}
  </div>

</div>

<!-- /rExtViewAltAppMylandpage.tpl en geozzy module -->



