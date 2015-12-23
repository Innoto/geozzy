<!DOCTYPE html>
<!-- defaultConHeader.tpl en app de Geozzy -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
  <title>{block name="headTitle"}galiciaagochada{/block}</title>

  {block name="headCssIncludes"}{$css_includes}{/block}
  {block name="headJsIncludes"}{$js_includes}{/block}

</head>
<body data-spy="scroll" data-target=".headContent">
  <article>
    <header class="headContent">
      {block name="headContent"}
        {include file="header.tpl"}
      {/block}
    </header>

    <section class="bodyContent">
      {block name="bodyContent"}{if $bodyContent}{$bodyContent}{/if}{/block}
    </section>

    <footer class="footerContent">
      {block name="footerContent"}
        {include file="footer.tpl"}
      {/block}
    </footer>
  </article>
</body>
</html>

<!-- /defaultConHeader.tpl en app de Geozzy -->
