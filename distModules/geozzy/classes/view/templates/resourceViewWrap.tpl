<!-- resourceViewWrap.tpl en geozzy module -->

<p> --- resourceViewWrap en geozzy module --- </p>

<div class="resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}
  {$resTemplateBlock}

</div><!-- /.resViewBlock -->

<!-- /resourceViewWrap.tpl en geozzy module -->
