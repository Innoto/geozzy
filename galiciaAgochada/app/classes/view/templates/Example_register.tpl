<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>galiciaagochada</title>

  <script src="https://maps.googleapis.com/maps/api/js">  </script>
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
