{extends file="default.tpl"}



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

  {if isset($htmlMsg)}
  <div class="htmlMsg">{$htmlMsg}</div>
  {else}

  <p> --- resourceViewPage.tpl en geozzy module --- </p>

  {$resourceBlock}

  {/if}

<!-- /resourceViewPage.tpl en geozzy module -->
{/block}
