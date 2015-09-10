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
<!-- coleccion.tpl en app de Geozzy -->

<p>GLOBAL LANG: {$langCogumelo}</p>

  <h2 style="color:#30494E;">GEOZZY APP - {t}View Collection{/t}</h2>

  <h3>{t}Collection{/t}</h3>

  <div class="resource">

    <div class="title"><label class="cgmMForm">{t}Title{/t}</label>
    {$title|escape:'htmlall'}</div>

    <div class="shortDescription"><label class="cgmMForm">{t}Short description{/t}</label>
    {$shortDescription|escape:'htmlall'}</div>

    <div class="mediumDescription"><label for="mediumDescription" class="cgmMForm">{t}Medium description{/t}</label>
    {$mediumDescription}</div>

    <div class="content"><label for="content" class="cgmMForm">{t}Content{/t}</label>
    {$content}</div>

    <div class="image cgmMForm-fileField "><label for="imgResource" class="cgmMForm">{t}Image{/t}</label>
    {$image}</div>

    <!-- div class="defaultZoom"><label class="cgmMForm">defaultZoom</label>
    {$defaultZoom}</div -->

  </div>

<!--
{$allData}
-->

  <hr>

<!--
<pre>
GLOBALES SMARTY

langDefault:{$langDefault}
langDefault i18n:{$langAvailable.$langDefault.i18n}
langAvailable:
{foreach $langAvailable as $idLang=>$arrLang}
  lang: {$idLang}
  {foreach $arrLang as $langKey=>$langValue}
    {$langKey} => {$langValue}
  {/foreach}
{/foreach}
</pre>
-->

<!-- /coleccion.tpl en app de Geozzy -->
{/block}
