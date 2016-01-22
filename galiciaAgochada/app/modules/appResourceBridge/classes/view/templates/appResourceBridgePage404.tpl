{extends file="primary.tpl"}


{block name="headClientIncludes" append}
  <script rel="false" type="text/javascript" src="/media/js/resource.js"></script>
{/block}

{block name="headTitle" append}{$title404}{/block}

{block name="bodyContent"}
<div class="content404">
  {$content404}
</div>
{/block}
