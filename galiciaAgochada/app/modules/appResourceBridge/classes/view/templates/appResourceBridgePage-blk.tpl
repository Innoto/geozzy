{extends file="primary.tpl"}

<!-- appResourceBridgePage-blk.tpl en appResourceBridge module -->

{block name="headContent"}{/block}
{block name="headClientIncludes"}
  {$client_includes}
{/block}

{block name="bodyContent"}
  {$resTemplateBlock}
{/block}

<script rel="false" type="text/javascript" src="/media/js/resource.js"></script>

<!-- /appResourceBridgePage-blk.tpl en appResourceBridge module -->
