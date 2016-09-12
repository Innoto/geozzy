{extends file="primary.tpl"}


{block name="headClientIncludes" append}
{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key)}{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}{/if}
  <script type="text/javascript" src="{$gMaps}&libraries=places"></script>
  <script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>
{/block}


{* SIN saltos de linea *}
{block name="headTitle" append}{if isset($res.data.headTitle) && $res.data.headTitle!=''}{$res.data.headTitle}{else}{$res.data.title}{/if}{/block}


{* SIN saltos de linea *}
{block name="headDescription"}{if $res.data.headDescription!=''}{$res.data.headDescription}{else}{$res.data.shortDescription}{/if}{/block}


{block name="socialMeta" append}
{$lang = $cogumelo.publicConf.C_LANG}
  <meta property="og:site_name" content="Galicia Agochada | Geozzy">
  <meta property="og:locale" content="{$cogumelo.publicConf.lang_available[$lang]['i18n']}">
  <meta property="og:url" content="{$cogumelo.publicConf.site_host}{$res.data["urlAlias"]}">
  <meta property="og:type" content="article">
  <meta property="og:title" content="{if isset($res.data.headTitle) && $res.data.headTitle!=''}{$res.data.headTitle}{else}{$res.data.title}{/if}">
{if isset($res.ext.rextSocialNetwork.data["textFb"])}
  <meta property="og:description" content="{$res.ext.rextSocialNetwork.data["textFb"]|escape:"html"}">
{/if}
{if isset($res.data.image.id)}
  <meta property="og:image" content="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/big/{$res.data.image.id}.jpg">
  <link rel="image_src" href="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg">
{/if}
{/block}


{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
