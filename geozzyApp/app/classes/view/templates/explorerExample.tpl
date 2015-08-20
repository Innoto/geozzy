<!DOCTYPE html>
<!-- portada.tpl en app de Geozzy -->
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=Edge"><![endif]-->
  <title>Geozzy app</title>

  {$css_includes}
  {$js_includes}

</head>
<body style="background:#E9B6B7;">
  <div id="explorerList"></div>
{literal}
<script>
  $( document ).ready(function() {

    var explorer = new geozzy.explorer({debug:true});
    explorer.exec();

  });

</script>
{/literal}
</body>
</html>
<!-- /portada.tpl en app de Geozzy -->
