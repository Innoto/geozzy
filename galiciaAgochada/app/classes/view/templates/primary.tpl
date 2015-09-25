<!DOCTYPE html>
<!-- defaultConHeader.tpl en app de Geozzy -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <title>{block name="headTitle"}galiciaagochada{/block}</title>

  {block name="headCssIncludes"}{$css_includes}{/block}
  {block name="headJsIncludes"}{$js_includes}{/block}

</head>
<body>

  <div class="headContent">
    {block name="headContent"}
      {include file="header.tpl"}
    {/block}
  </div>

  <div class="bodyContent">
    {block name="bodyContent"}{/block}
  </div>

</body>
</html>

<!-- /defaultConHeader.tpl en app de Geozzy -->
