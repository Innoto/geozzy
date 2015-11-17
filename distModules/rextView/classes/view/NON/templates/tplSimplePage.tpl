<!-- tplSimplePage.tpl en rExtView module -->

<p> --- tplSimplePage.tpl en rExtView module --- </p>

<div class="resViewBlock tplSimplePage">

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

<!-- /tplSimplePage.tpl en geozzy module -->



