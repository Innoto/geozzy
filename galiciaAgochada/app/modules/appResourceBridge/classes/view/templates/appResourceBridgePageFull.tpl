{extends file="primary.tpl"}


{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?language={$GLOBAL_LANG_AVAILABLE[$GLOBAL_C_LANG].i18n}"></script>
  <script rel="false" type="text/javascript" src="/media/js/resource.js"></script>
{/block}

{block name="headTitle" append}
  {$res.data.headTitle}
{/block}

{block name="socialMeta" append}
<meta property="og:title" content="{$res.data.headTitle}" />
<meta property="og:type" content="article" />
<meta property="og:site_name" content="Galicia Agochada" />
<meta property="og:image" content="{$site_host}/cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg" />
<link href="{$site_host}/cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg" rel="image_src">
<meta property="og:url" content="{$site_host}{$res.data["urlAlias_$GLOBAL_C_LANG"]}" />
<meta property="og:description" content="{$res.ext.rextSocialNetwork.data["textFb_$GLOBAL_C_LANG"]}" />
{/block}

{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
