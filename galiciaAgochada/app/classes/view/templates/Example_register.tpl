<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>galiciaagochada</title>

{$gMaps = "https://maps.googleapis.com/maps/api/js?language=`$cogumelo.publicConf.lang_available[$cogumelo.publicConf.C_LANG].i18n`"}
{if isset($cogumelo.publicConf.google_maps_key) && $cogumelo.publicConf.google_maps_key}
{$gMaps = "`$gMaps`&key=`$cogumelo.publicConf.google_maps_key`"}
{/if}
  <script type="text/javascript" src="{$gMaps}&libraries=places"></script>
  {$main_client_includes}
  {$client_includes}

</head>
<body>

<div class="registerFormContainer">
  {$userFormOpen}
    {foreach from=$userFormFields key=key item=field}
      {$field}
    {/foreach}
  {$userFormClose}
  {$userFormValidations}

</div>

</body>
</html>
<!-- /portada.tpl en app de Geozzy -->
