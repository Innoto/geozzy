<!DOCTYPE html>
<!-- defaultConHeader.tpl en app de Geozzy -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
  <title>{block name="headTitle"}Galicia Agochada |{/block}</title>
  {block name="socialMeta"}{/block}
  {block name="headClientIncludes"}{$client_includes}{/block}

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


    {block name="footerContent"}
      <footer class="footerContent">
        {include file="footer.tpl"}
      </footer>
    {/block}

  </article>
</body>
</html>

<!-- /defaultConHeader.tpl en app de Geozzy -->
