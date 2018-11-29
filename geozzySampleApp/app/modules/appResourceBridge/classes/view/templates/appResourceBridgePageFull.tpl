{extends file="primary.tpl"}


{block name="headClientIncludes" append}
{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key) && $cogumelo.publicConf.google_maps_key}
{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}
{/if}
  <script type="text/javascript" src="{$gMaps}&libraries=places"></script>
  <script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>
{/block}


{* SIN saltos de linea *}
{block name="headTitle" append}{if isset($res.data.headTitle) && $res.data.headTitle!=''}{$res.data.headTitle|escape:"html"}{else}{$res.data.title|escape:"html"}{/if}{/block}


{* SIN saltos de linea *}
{block name="headDescription"}{if isset($res.data.headDescription) && $res.data.headDescription!=''}{$res.data.headDescription|escape:"html"}{elseif isset($res.data.shortDescription)}{$res.data.shortDescription|escape:"html"}{/if}{/block}


{* SIN saltos de linea *}
{block name="headKeywords"}{if isset($res.data.headKeywords) && $res.data.headKeywords!=''}{$res.data.headKeywords|escape:"html"}{else}geozzySampleApp{/if}{/block}


{block name="socialMeta" append}
{$lang = $cogumelo.publicConf.C_LANG}
  <meta property="og:site_name" content="Geozzy Sample App">
  <meta property="og:locale" content="{$cogumelo.publicConf.lang_available[$lang]['i18n']}">
  <meta property="og:url" content="{$cogumelo.publicConf.site_host}{$res.data.urlAlias}">
  <meta property="og:type" content="article">
  <meta property="og:title" content="Geozzy Sample App. {if isset($res.data.headTitle) && $res.data.headTitle!=''}{$res.data.headTitle|escape:'html'}{else}{$res.data.title|escape:'html'}{/if}">
{if isset($res.ext.rextSocialNetwork.data["textFb"])}
  <meta property="og:description" content="{$res.ext.rextSocialNetwork.data['textFb']|escape:'html'}">
{/if}
{if isset($res.data.image.id)}
  <meta property="og:image" content="{$cogumelo.publicConf.site_host}{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/rrss/{$res.data.image.name|pathinfo:$smarty.const.PATHINFO_FILENAME}.jpg">
  <link rel="image_src" href="{$cogumelo.publicConf.site_host}{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/rrss/{$res.data.image.name|pathinfo:$smarty.const.PATHINFO_FILENAME}.jpg">
{/if}
{/block}


{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
