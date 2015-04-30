<!DOCTYPE html>
<html>
<head>
  <title>Geozzy API</title>
  <link href='//fonts.googleapis.com/css?family=Droid+Sans:400,700' rel='stylesheet' type='text/css'/>
  <link href='{$swaggerLocation}src/main/html/css/reset.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='{$swaggerLocation}src/main/html/css/screen.css' media='screen' rel='stylesheet' type='text/css'/>
  <link href='{$swaggerLocation}src/main/html/css/reset.css' media='print' rel='stylesheet' type='text/css'/>
  <link href='{$swaggerLocation}src/main/html/css/screen.css' media='print' rel='stylesheet' type='text/css'/>
  <script type="text/javascript" src="{$swaggerLocation}lib/shred.bundle.js"></script>
  <script src='{$swaggerLocation}lib/jquery-1.8.0.min.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/jquery.slideto.min.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/jquery.wiggle.min.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/jquery.ba-bbq.min.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/handlebars-1.0.0.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/underscore-min.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/backbone-min.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}lib/swagger.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}dist/swagger-ui.js' type='text/javascript'></script>
  <script src='{$swaggerLocation}/lib/highlight.7.3.pack.js' type='text/javascript'></script>

  <!-- enabling this will enable oauth2 implicit scope support -->
  <script src='{$swaggerLocation}lib/swagger-oauth.js' type='text/javascript'></script>

  <script type="text/javascript">
    $(function () {
      window.swaggerUi = new SwaggerUi({
      url: "/api/apidoc.json",
      dom_id: "swagger-ui-container",
      supportedSubmitMethods: ['get', 'post', 'put', 'delete'],
      onComplete: function(swaggerApi, swaggerUi){
        log("Loaded SwaggerUI");

        if(typeof initOAuth == "function") {
          /*
          initOAuth({
            clientId: "your-client-id",
            realm: "your-realms",
            appName: "your-app-name"
          });
          */
        }
        $('pre code').each(function(i, e) {
          hljs.highlightBlock(e)
        });
      },
      onFailure: function(data) {
        log("Unable to Load SwaggerUI");
      },
      docExpansion: "none",
      sorter : "alpha"
    });

    window.swaggerUi.load();
  });
  </script>

  <style type="text/css">
    .swagger-section #header {
      background-color: #bfe3e9;
    }
  </style>
</head>


<body class="swagger-section">
<div id='header'>
  <div class="swagger-ui-wrap">
    <a id="logo" href="http://swagger.wordnik.com"></a>

  </div>
</div>

<div id="message-bar" class="swagger-ui-wrap">&nbsp;</div>
<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
</body>
</html>
