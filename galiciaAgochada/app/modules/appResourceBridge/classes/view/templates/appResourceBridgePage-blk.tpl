
<!-- appResourceBridgePage-blk.tpl en appResourceBridge module -->
{block name="headClientIncludes"}
  {$client_includes}
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

<section class="bodyContentBlock">
  {$resTemplateBlock}
</section>
<script rel="false" type="text/javascript" src="/media/js/resource.js"></script>
{block name="footerContent"}
  <footer class="footerContent">
    {include file="footer.tpl"}
  </footer>
{/block}
<!-- /appResourceBridgePage-blk.tpl en appResourceBridge module -->
