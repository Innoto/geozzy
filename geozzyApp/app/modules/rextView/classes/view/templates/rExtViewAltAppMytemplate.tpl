<!-- rExtViewAltAppMytemplate.tpl en rExtView module -->

<p> --- rExtViewAltAppMytemplate.tpl en rExtView module --- </p>

<div class="resViewBlock rExtViewAltAppMytemplate">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <div class="title">
    <label class="cgmMForm">{t}Title{/t}</label>
    {$title|escape:'htmlall'}
  </div>

  <div class="content">
    <label for="content" class="cgmMForm">{t}Content{/t}</label>
    {$content}
  </div>

</div>

<!-- /rExtViewAltAppMytemplate.tpl en geozzy module -->



