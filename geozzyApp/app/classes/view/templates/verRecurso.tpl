{extends file="default.tpl"}

{block name="headCssIncludes" append}
<style type="text/css">
  label { color:green; padding: 5px 0; }
  .resource div { color:red; padding: 5px 20px; }
</style>
{/block}

{block name="headKeywords"}{$headKeywords}{/block}

{block name="headDescription"}{$headDescription}{/block}

{block name="headTitle" prepend}{$headTitle|default:$title|escape:'htmlall'} - {/block}

{block name="bodyContent"}
<!-- probandoFormRecurso.tpl en app de Geozzy -->

  <h2 style="color:#30494E;">GEOZZY APP - View Resource</h2>

  <h3>Resource</h3>

  <div class="resource">

    <div class="title"><label class="cgmMForm">title</label>
    {$title|escape:'htmlall'}</div>

    <div class="shortDescription"><label class="cgmMForm">shortDescription</label>
    {$shortDescription|escape:'htmlall'}</div>

    <div class="mediumDescription"><label for="mediumDescription" class="cgmMForm">mediumDescription</label>
    {$mediumDescription}</div>

    <div class="content"><label for="content" class="cgmMForm">content</label>
    {$content}</div>

    <!-- div class="image cgmMForm-fileField "><label for="imgResource" class="cgmMForm">image</label>
    {$image}</div -->

    <!-- div class="defaultZoom"><label class="cgmMForm">defaultZoom</label>
    {$defaultZoom}</div -->

  </div>

<!--
{$resourceHtml}
-->

  <hr>

<!-- /probandoFormRecurso.tpl en app de Geozzy -->
{/block}
