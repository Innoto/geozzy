{extends file="primary.tpl"}

{block name="headJsIncludes" append}
  <script src="https://maps.googleapis.com/maps/api/js"></script>
{/block}

{block name="bodyContent"}
  <div class="rinconsExplorer">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}

{block name="footerContent"}

{/block}
