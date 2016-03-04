<!DOCTYPE html>
<!-- defaultConHeader.tpl en app de Geozzy -->
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="robots" content="noindex, nofollow, noarchive, nosnippet">
  <title>{block name="headTitle"}Galicia Agochada | {/block}</title>
  <meta name="description" content="{block name="headDescription"}Galicia Agochada{/block}">
  {block name="socialMeta"}{/block}
  {block name="headClientIncludes"}
    {$main_client_includes}
    {$client_includes}
    <script type="text/javascript">
      $( document ).ready(function(){
        feature.testAll();
      });
    </script>
  {/block}

</head>
<body data-spy="scroll" data-target=".headContent">
  <article>

    {capture "headContent"}
      {block name="headContent"}
        <header class="headContent">
          {include file="header.tpl"}
        </header>
      {/block}
    {/capture}
    {if isset($res.header) && $res.header!==false}{$res.header}{/if}
    {if !isset($res.header) || $res.header===true}{$smarty.capture.headContent}{/if}

    <section class="bodyContent">
      {block name="bodyContent"}{if $bodyContent}{$bodyContent}{/if}{/block}
    </section>

    {capture "footerContent"}
      {block name="footerContent"}
        <footer class="footerContent">
          {include file="footer.tpl"}
        </footer>
      {/block}
    {/capture}
    {if isset($res.footer) && $res.footer!==false}{$res.footer}{/if}
    {if !isset($res.footer) || $res.footer===true}{$smarty.capture.footerContent}{/if}

  </article>
</body>
</html>


<!-- /defaultConHeader.tpl en app de Geozzy -->
