<!-- rExtViewAltLandingHotelCollections.tpl en rExtView module -->

<p> --- rExtViewAltLandingHotelCollections.tpl en rExtView module --- </p>

<div class="resViewBlock tplSimplePage">

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

<!-- /rExtViewAltLandingHotelCollections.tpl en geozzy module -->



