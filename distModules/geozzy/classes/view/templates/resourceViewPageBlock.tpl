
{block name="bodyContent"}
<!-- resourceViewPage.tpl en geozzy module -->

  {if isset($htmlMsg)}
  <div class="htmlMsg">{$htmlMsg}</div>
  {else}

  <p> --- resourceViewPage.tpl en geozzy module --- </p>

  {$resourceBlock}

  {block name="headCssIncludes"}{$css_includes}{/block}

  {block name="headJsIncludes"}{$js_includes}{/block}

  {/if}

<!-- /resourceViewPageBlock.tpl en geozzy module -->
{/block}
