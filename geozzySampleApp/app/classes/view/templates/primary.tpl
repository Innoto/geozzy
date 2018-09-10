 <!DOCTYPE html>
{assign var=langPart value="_"|explode:$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG]['i18n']}
<html lang="{$langPart[0]}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {* <meta name="robots" content="noindex, nofollow, noarchive, nosnippet"> *}



  <title>{block name="headTitle"} | {/block}</title>



  <meta name="description" content="{block name="headDescription"}{/block}">



  <meta name="keywords" content="{block name="headKeywords"}{/block}">




  <link rel="canonical" href="{$cogumelo.publicConf.site_host}{$res.data['urlAlias']}">
  {block name="socialMeta"}{/block}
  {block name="headClientIncludes"}
    {$main_client_includes}
    {$client_includes}
    <script type="text/javascript"> $( document ).ready(function(){ feature.testAll(); }); </script>
  {/block}
</head>

<body class="{$bodySection|default:''}{if !empty($res.labels)} labelsInfo {' '|implode:$res.labels}{/if}" data-section="{$bodySection|default:''}" data-labels="{if !empty($res.labels)}{','|implode:$res.labels}{/if}">
  <article>
    {capture "headContent"}
      {block name="headContent"}
        <header class="headContent">
          {include file="header.tpl"}
        </header>
      {/block}
    {/capture}
    {if isset($res.header) && $res.header!==false && $res.header!==true}{$res.header}{/if}
    {if !isset($res.header) || $res.header===true}{$smarty.capture.headContent}{/if}

    <section class="bodyContent">
      {block name="bodyContent"}{if $bodyContent}{$bodyContent}{/if}{/block}
      <div id="playModalVideo" style="display:none;"></div>
      <div id="detectMediaqueriesStyles" style="display:none;"></div>
    </section>

    {capture "footerContent"}
      {block name="footerContent"}
        <footer class="footerContent">
          {include file="footer.tpl"}
        </footer>
      {/block}
    {/capture}
    {if isset($res.footer) && $res.footer!==false && $res.footer!==true}{$res.footer}{/if}
    {if !isset($res.footer) || $res.footer===true}{$smarty.capture.footerContent}{/if}

  </article>




<!-- Cookie EU Script 2018 -->
{if isset($cogumelo.publicConf.google_analytics_key) && $cogumelo.publicConf.google_analytics_key}
<script>
function loadGoogleAnalytics() {
  // console.log('loadGoogleAnalytics');

  (function(i,s,o,g,r,a,m) { i['GoogleAnalyticsObject']=r;i[r]=i[r]||function() {
  (i[r].q=i[r].q||[]).push(arguments) },i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', '{$cogumelo.publicConf.google_analytics_key}', 'auto');
  ga('set', 'anonymizeIp', true);
  ga('send', 'pageview');
}
</script>
{else}
<script>function loadGoogleAnalytics() { }</script>
{/if}


{literal}
<script>
var ckAceptoName = 'ck-acepto-eu-law-2018';

if( document.cookie.replace(' ','').indexOf(ckAceptoName+'=1') !== -1 ) {
  loadGoogleAnalytics();
}


if( typeof jQuery !== 'undefined' ) {
  if( $('#ck-block-eu-law').length<1 && document.cookie.replace(' ','').indexOf(ckAceptoName+'=1')==-1 ) {

    var ckBotAceptar = 'Aceptar';
    var ckBlockMsg = 'Utilizamos cookies propias y de terceros para permitirte la navegación en '+
      'nuestra web y mejorar nuestros servicios. Puedes cambiar la configuración u obtener más información '+
      '<a style="font-weight:700;color:#CCC;text-decoration:underline;" '+
      'href="/'+cogumelo.publicConf.C_LANG+'/politica-cookies">aquí</a>. '+
      'Pulsa el botón "'+ckBotAceptar+'" para confirmar que has leído y aceptado esta información.';

    var ckBotStyle = 'float:right;margin:5px 0 5px 20px;padding:6px;font-weight:700;'+
      'color:#333;background-color:#ACA;cursor:pointer';
    var ckBlocStyle = 'position:fixed;bottom:0;width:100%;padding:25px;'+
      'color:#CCC;background-color:rgba(22,22,22,0.9);font-size:16px;line-height:26px;'+
      'border:2px solid #EEE;z-index:99999;';

    jQuery('body').append(''+
    '<div id="ck-block-eu-law" style="'+ckBlocStyle+'">'+
      '<div style="margin:0 auto;padding:15px;">'+
        '<span style="margin:0;padding:0;">'+
          '<a class="acepto" style="'+ckBotStyle+'">'+ckBotAceptar+'</a>'+
          ckBlockMsg+
        '</span>'+
      '</div>'+
    '</div>');
    $('#ck-block-eu-law a.acepto').click(function(){
      // var alto=$('#ck-block-eu-law').outerHeight(true);
      // var marxe=$('body').css('marginBottom').replace('px','');
      // $('body').css( 'marginBottom', ''+ (parseInt(marxe)-parseInt(alto)) + 'px' );
      $('#ck-block-eu-law').hide();
      var fecha = new Date();
      fecha.setTime(fecha.getTime()+(365*24*60*60*1000));
      document.cookie = ckAceptoName+'=1; expires='+fecha.toGMTString()+'; path=/';

      loadGoogleAnalytics();

      return false;
    });
    // var alto=$('#ck-block-eu-law').outerHeight(true);
    // var marxe=$('body').css('marginBottom').replace('px','');
    // $('body').css( 'marginBottom', ''+ (parseInt(marxe)+parseInt(alto)) + 'px' );
  }
}
</script>
{/literal}
<!-- /Cookie EU Script 2018 -->



</body>
</html>
