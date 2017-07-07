 <!DOCTYPE html>
{assign var=langPart value="_"|explode:$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG]['i18n']}
<html lang="{$langPart[0]}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {* <meta name="robots" content="noindex, nofollow, noarchive, nosnippet"> *}



  <title>{block name="headTitle"}Programa de Formación para Profesores Fundación Amancio Ortega | {/block}</title>



  <meta name="description" content="{block name="headDescription"}50 plazas de formación y prácticas para el curso académico 2017-2018 en Canadá{/block}">



  <meta name="keywords" content="{block name="headKeywords"}formación,profesores,becas,fundación,canadá,amancio,ortega,zara,inditex,prácticas,máster,universitario,ESO,bachillerato,profesional{/block}">




  <link rel="canonical" href="{$cogumelo.publicConf.site_host}{$res.data['urlAlias']}">
  <link href="https://fonts.googleapis.com/css?family=Anton|Arimo" rel="stylesheet">

  {block name="socialMeta"}{/block}
  {block name="headClientIncludes"}
    {$main_client_includes}
    {$client_includes}
    <script type="text/javascript">$( document ).ready(function(){feature.testAll();});</script>
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




{literal}
<!-- Cookie EU Script -->
<script>
if(typeof jQuery!='undefined') {
  if( $('#ck-block-eu-law').length<1 && document.cookie.replace(' ','').indexOf('ck-acepto-eu-law=1')==-1 ) {
    jQuery('body').append(''+
    '<div id="ck-block-eu-law" style="position:fixed;bottom:0;width:100%;padding:5px 0;background-color:#333;z-index:999;">'+
      '<div style="margin:0 auto;max-width:950px;padding:5px;">'+
        '<span style="margin:0;padding:0;font-size:12px;color:#CCC;">'+
          '<a class="acepto" style="float:right;margin:10px 10px 3px 10px;padding:2px '+
          ' 6px;font-weight:700;color:#333;background-color:#CCC;cursor:pointer">aceptar</a>'+
          'Utilizamos cookies propias y de terceros para mejorar nuestros servicios mediante el análisis de sus hábitos de navegación. '+
          'Si continúa navegando, consideramos que acepta su uso. Puede obtener más información, o bien conocer cómo cambiar la '+
          'configuración, en nuestra '+
          ' <a style="font-weight:700;color:#CCC;" href="/politica-cookies">Política de uso de cookies.</a>.'+
        '</span>'+
      '</div>'+
    '</div>');
    $('#ck-block-eu-law a.acepto').click(function(){
      var alto=$('#ck-block-eu-law').outerHeight(true);
      var marxe=$('body').css('marginBottom').replace('px','');
      $('body').css( 'marginBottom', ''+ (parseInt(marxe)-parseInt(alto)) + 'px' );
      $('#ck-block-eu-law').hide();
      var fecha = new Date();
      fecha.setTime(fecha.getTime()+(365*24*60*60*1000));
      document.cookie = "ck-acepto-eu-law=1; expires="+fecha.toGMTString()+"; path=/";
      return false;
    });
    var alto=$('#ck-block-eu-law').outerHeight(true);
    var marxe=$('body').css('marginBottom').replace('px','');
    $('body').css( 'marginBottom', ''+ (parseInt(marxe)+parseInt(alto)) + 'px' );
  }
}
</script>
<!-- /Cookie EU Script -->
{/literal}

{literal}
<!-- Analytics -->
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-57754094-3', 'auto');
  ga('send', 'pageview');

</script>
<!-- /Analytics -->
{/literal}


</body>
</html>
