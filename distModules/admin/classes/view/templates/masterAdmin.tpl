<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Geozzy Admin</title>

  <!-- Timeline CSS -->
  <!--<link href="css/plugins/timeline.css" rel="stylesheet">-->


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="/vendor/bower/html5shiv/dist/html5shiv.js"></script>
      <script src="/vendor/bower/respond/dest/respond.min.js"></script>
  <![endif]-->
  {$css_includes}
  {$js_includes}

</head>

<body>

    <div id="wrapper">

      <!-- Navigation -->
      <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        {include file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/adminHeader.tpl"}
      </nav>

      <div id="page-wrapper">
        {block name="masterContent"}{/block}
      </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->


</body>

</html>
