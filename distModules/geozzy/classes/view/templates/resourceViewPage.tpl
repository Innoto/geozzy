{extends file="default.tpl"}

{block name="headCssIncludes" append}
<style type="text/css">
  label { color:green; padding: 5px 0; }
  .resource div { color:red; padding: 5px 20px; }
</style>
{/block}


{block name="headTitle" prepend}
{if $headTitle or $title}{$headTitle|default:$title|escape:'htmlall'} - {/if}
{/block}

{block name="headKeywords" append}{if $headKeywords} {$headKeywords}{/if}{/block}

{block name="headDescription"}
{if $headDescription}{$headDescription}
{else}{$smarty.block.parent}{/if}
{/block}



{block name="bodyContent"}
<!-- resourceViewPage.tpl en geozzy module -->
<p>resourceViewPage.tpl en geozzy module</p>

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <h2 style="color:#30494E;">GEOZZY APP - {t}View Resource{/t}</h2>
  <h3>{t}Resource{/t}</h3>
  <div class="resource">
    {$resourceBlock}
  </div>

<!-- /resourceViewPage.tpl en geozzy module -->
{/block}
