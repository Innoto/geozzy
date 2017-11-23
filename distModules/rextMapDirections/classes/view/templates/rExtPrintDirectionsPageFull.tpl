<!DOCTYPE html>
{assign var=langPart value="_"|explode:$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG]['i18n']}
<html lang="{$langPart[0]}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {* <meta name="robots" content="noindex, nofollow, noarchive, nosnippet"> *}

  <title>{block name="headTitle"}{t}Print Map Directions{/t}{/block}</title>
  <meta name="description" content="{block name="headDescription"}{/block}">
  <meta name="keywords" content="{block name="headKeywords"}{/block}">


  {* <link rel="canonical" href="{$cogumelo.publicConf.site_host}{$res.data['urlAlias']}"> *}
  {* {block name="socialMeta"}{/block} *}
  {block name="headClientIncludes"}
    {$main_client_includes}
    {$client_includes}
    <script type="text/javascript"> $( document ).ready(function(){ feature.testAll(); }); </script>
  {/block}
</head>

<body class="printMapDirections labelsInfo print rExtMapDirections" data-section="printMapDirections">

  <article>
    {* {capture "headContent"}
      {block name="headContent"}
        <header class="headContent">
          {include file="header.tpl"}
        </header>
      {/block}
    {/capture}
    {if isset($res.header) && $res.header!==false && $res.header!==true}{$res.header}{/if}
    {if !isset($res.header) || $res.header===true}{$smarty.capture.headContent}{/if} *}

    <section class="bodyContent">
      {block name="bodyContent"}{if $bodyContent}{$bodyContent}{/if}{/block}
      <div id="detectMediaqueriesStyles" style="display:none;"></div>
    </section>

    {* {capture "footerContent"}
      {block name="footerContent"}
        <footer class="footerContent">
          {include file="footer.tpl"}
        </footer>
      {/block}
    {/capture}
    {if isset($res.footer) && $res.footer!==false && $res.footer!==true}{$res.footer}{/if}
    {if !isset($res.footer) || $res.footer===true}{$smarty.capture.footerContent}{/if} *}

  </article>

{if isset($cogumelo.publicConf.google_analytics_key) && $cogumelo.publicConf.google_analytics_key}
<!-- Analytics -->
<script>
{literal}
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
{/literal}

ga('create', '{$cogumelo.publicConf.google_analytics_key}', 'auto');
ga('send', 'pageview');
</script>
<!-- /Analytics -->
{/if}

</body>
</html>
