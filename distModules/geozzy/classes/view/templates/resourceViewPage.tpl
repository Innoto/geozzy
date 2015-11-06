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

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>
  {else}

  <p> --- resourceViewPage.tpl en geozzy module --- </p>
  <div class="titleSec gzSection">
    <div class="container">
      <div class="title col-lg-10">
        <img alt="typeIcon" src="/mediaCache/module/rtypeHotel/img/icon.png"/>
        <h1>{$title|escape:'htmlall'}</h1>
      </div>
      <div class="stars col-lg-2">
        <div class="col-lg-2">
          <i class="fa fa-star-o"></i>
        </div>
        <div class="col-lg-2">
          <i class="fa fa-star-o"></i>
        </div>
        <div class="col-lg-2">
          <i class="fa fa-star-o"></i>
        </div>
        <div class="col-lg-2">
          <i class="fa fa-star-o"></i>
        </div>
        <div class="col-lg-2">
          <i class="fa fa-star-o"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="resource">
    {$resourceBlock}
  </div>

  {/if}

<!-- /resourceViewPage.tpl en geozzy module -->
{/block}
