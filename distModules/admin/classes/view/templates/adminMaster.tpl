<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=no">
  {if !isset($titleCustom)}
    <title>Geozzy Admin</title>
  {else}
    <title>{$titleCustom}</title>
  {/if}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="/vendor/bower/html5shiv/dist/html5shiv.js"></script>
      <script src="/vendor/bower/respond/dest/respond.min.js"></script>
  <![endif]-->

{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key) && $cogumelo.publicConf.google_maps_key}
{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}
{/if}
  <script type="text/javascript" src="{$gMaps}&libraries=places"></script>
  {$main_client_includes}
  {$client_includes}


{*$cogumelo|var_dump*}



</head>

<body>

  <!-- Client templates -->
  {include file="admin///categoryEditor.tpl"}
  {include file="admin///menuTermEditor.tpl"}
  {include file="admin///resourcesStarredList.tpl"}
  {include file="admin///modal.tpl"}


  <div id="wrapper" {if !empty($menuClosed)} class="closed" {/if}>
    {include file="admin///adminMenu.tpl"} <!--menu-wrapper -->
    <div id="page-wrapper"><!--Content -->
    </div><!-- /#page-wrapper -->
  </div><!-- /#wrapper -->

</body>

</html>
