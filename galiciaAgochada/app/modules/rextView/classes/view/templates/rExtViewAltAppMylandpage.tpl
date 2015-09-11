<!-- rExtViewAltAppMylandpage.tpl en rExtView module -->

<p> --- rExtViewAltAppMylandpage.tpl en rExtView module --- </p>

<div class="resViewBlock rExtViewAltAppMylandpage">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="altViewInfo">
    <label class="cgmMForm">{t}Alternate view{/t}</label>
    {$altViewInfo|escape:'htmlall'}
  </div>

  <div class="title">
    <label class="cgmMForm">{t}Title{/t}</label>
    {$title|escape:'htmlall'}
  </div>

  <div class="content">
    <label for="content" class="cgmMForm">{t}Content{/t}</label>
    {$content}
  </div>

</div>

<!-- /rExtViewAltAppMylandpage.tpl en geozzy module -->



