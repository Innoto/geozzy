<!DOCTYPE html>
<!-- defaultConHeader.tpl en app de Geozzy -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>{block name="headTitle"}Geozzy app{/block}</title>

  {block name="headCssIncludes"}{$css_includes}{/block}
  {block name="headJsIncludes"}{$js_includes}{/block}

</head>
<body style="background:#BFE3E9;">

  <div class="headContent">
    {block name="headContent"}Este é o espacio da cabeceira...{/block}
  </div>

  <div class="bodyContent">
    {block name="bodyContent"}{/block}
  </div>

</body>
</html>

<!-- /defaultConHeader.tpl en app de Geozzy -->
