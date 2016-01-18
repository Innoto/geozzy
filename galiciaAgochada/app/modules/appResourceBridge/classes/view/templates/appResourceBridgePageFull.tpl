{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script rel="false" type="text/javascript" src="/media/js/resource.js"></script>
{/block}


{block name="headTitle" append}
  {$res.data.headTitle}
{/block}

{block name="socialMeta" append}
  <meta name="description" content="{$res.ext.rextSocialNetwork.data["textFb_$GLOBAL_C_LANG"]}" />
{/block}

{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
