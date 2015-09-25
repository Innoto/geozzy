{extends file="primary.tpl"}

{block name="headJsIncludes" append}
  <script src="https://maps.googleapis.com/maps/api/js"></script>
{/block}

{block name="bodyContent"}
  <div class="paisaxesExplorer">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}
