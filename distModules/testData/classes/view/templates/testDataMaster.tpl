<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=0.8, user-scalable=no">
  <title>Geozzy TestData Module</title>
</head>

<body>
  <div id="wrapper">
    <div id="page-wrapper"><!--Content -->
      <div style="background-color:#A5BFD2;">
        <div style="width:60%;margin:auto;height:250px;">
          <h3 style="padding-top:15px;">Benvidxs ao xerador de datos de proba!</h3>
          <div style="padding-top:30px;">
            <p>Cantos recursos queres?</p>
            <input style="width:50px;" id="resNum" value=""/>
          </div>  
          <div id="generar" style="border:1px; background-color:#D7E3EA;width:140px;padding:5px;margin-top:30px;text-align:center;font-weight:bold; border-radius:8px;cursor:pointer;"> Xerar agora! </div>
        </div>  
      </div>  
    </div><!-- /#page-wrapper -->
  </div><!-- /#wrapper -->

</body>
  <script type="text/javascript" src="/vendor/bower/jquery/dist/jquery.js"></script>
  <script>
    $('#generar').click(function(){
        window.location = 'resources/'+$('#resNum').val();
      });
  </script>

</html>
