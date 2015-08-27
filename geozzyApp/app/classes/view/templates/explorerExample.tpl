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
  <script src="https://maps.googleapis.com/maps/api/js">  </script>

</head>
<body style="background:#E9B6B7;">
  <div id="explorerMap" style="width:100%;height:400px;background-color:grey;"></div>
  <div id="explorerList"></div>
{literal}
<script>
  $( document ).ready(function() {


    // gmaps init
    var mapOptions = {
      center: { lat: 43.1, lng: -7.36 },
      zoom: 7
    };
    resourceMap = new google.maps.Map( document.getElementById('explorerMap'), mapOptions);


    var explorer = new geozzy.explorer({debug:true});
    explorer.exec();

  });

</script>
{/literal}
</body>
</html>
<!-- /portada.tpl en app de Geozzy -->
