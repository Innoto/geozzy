{extends file="default.tpl"}

{block name="headCssIncludes" append}
<style type="text/css">
  label { color:green; padding: 5px 0; }
  .resource div { }
  .bodyContent{
    background-color:#fff;
    width: 960px;
    margin:auto;
  }
  .resViewBlock{
    padding:20px;
  }
  .resViewBlock .image{
      width: 960px;
  }
</style>
{/block}

{block name="headTitle" prepend}
{if $headTitle or $title}{$headTitle|default:$title|escape:'htmlall'} - {/if}
{/block}

{block name="headKeywords" append}{if isset($headKeywords) and $headKeywords} {$headKeywords}{/if}{/block}

{block name="headDescription"}
{if isset($headDescription) and $headDescription}{$headDescription}
{else}{$smarty.block.parent}{/if}
{/block}



{block name="bodyContent"}
<!-- resourceViewPage.tpl en geozzy module -->
<p> --- resourceViewPage.tpl en geozzy module --- </p>

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>
  {else}
  <h2>
    <div class="title">
      {$title|escape:'htmlall'}
    </div>
  </h2>

  <div class="resource">
    {$resourceBlock}
  </div>

  {/if}

<!-- /resourceViewPage.tpl en geozzy module -->
{/block}
