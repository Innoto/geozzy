{extends file="primary.tpl"}

{if isset($res.header) && $res.header===false}
  {block name="headContent"}{/block}
{/if}

{if isset($res.footer) && $res.footer===false}
  {block name="footerContent"}{/block}
{/if}

{block name="headClientIncludes" append}
  <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places&language={$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n}"></script>
  <script rel="false" type="text/javascript" src="{$cogumelo.publicConf.media}/js/resource.js"></script>
{/block}

{block name="headTitle" append}
  {if $res.data.headTitle!='' }
    {$res.data.headTitle}
  {else}
    {$res.data.title}
  {/if}
{/block}

{block name="headDescription"}{if $res.data.headDescription!='' }{$res.data.headDescription}{else}{$res.data.shortDescription}{/if}{/block}

{block name="socialMeta" append}
<meta property="og:title" content="{$res.data.headTitle}" />
<meta property="og:type" content="article" />
<meta property="og:site_name" content="Galicia Agochada" />
<meta property="og:image" content="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg" />
<link href="{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}/fast/{$res.data.image.id}.jpg" rel="image_src">
<meta property="og:url" content="{$site_host}{$res.data["urlAlias"]}" />
<meta property="og:description" content="{$res.ext.rextSocialNetwork.data["textFb"]}" />
{/block}

{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
