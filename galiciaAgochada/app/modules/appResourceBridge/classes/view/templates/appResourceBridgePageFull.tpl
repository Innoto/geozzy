{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script rel="false" type="text/javascript" src="/media/js/resource.js"></script>
{/block}

{block name="socialMeta" append}
  <title>Mi titulo</title>
  <meta name="description" content="Mi texto a compartir." />
{/block}

{block name="bodyContent"}
<!-- appResourceBridgePageFull.tpl en appResourceBridge module -->
  {$resTemplateBlock}
<!-- /appResourceBridgePageFull.tpl en appResourceBridge module -->
{/block}
