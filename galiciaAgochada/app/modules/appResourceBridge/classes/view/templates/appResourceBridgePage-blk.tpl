
<!-- appResourceBridgePage-blk.tpl en appResourceBridge module -->
{block name="headClientIncludes"}
  {$client_includes}
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
