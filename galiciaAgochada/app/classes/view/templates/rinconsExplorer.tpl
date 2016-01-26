{extends file="primary.tpl"}

{block name="headClientIncludes" append}
  <script src="https://maps.googleapis.com/maps/api/js"></script>
{/block}

{block name="bodyContent"}
  <div class="rinconsExplorer explorerCommonBase">
    {include file="explorer///explorer.tpl"}
  </div>
{/block}

{block name="footerContent"}

{/block}
