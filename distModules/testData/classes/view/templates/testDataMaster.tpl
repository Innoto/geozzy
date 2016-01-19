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
      <div style="">
        <form method="post" action="/testData/generate">
          <div style="width:60%;margin:auto;">
            <h3 style="padding-top:15px;">Xerador de datos de proba:</h3>
            <div style="padding-top:10px;">
              <p>Records</p>
              <input style="width:50px;" name="resNum" value="100"/>
            </div>
            <div>
              <p>Initial Coord</p>
              lat: <input style="width:50px;" name="lat1" value="41.75246"/>
              lon: <input style="width:50px;" name="lng1" value="-9.22851"/>
            </div>
            <div>
              <p>Final Coord</p>
              lat: <input style="width:50px;" name="lat2" value="44.05364"/>
              lon: <input style="width:50px;" name="lng2" value="-6.09741"/>
            </div>
            <input type="submit" id="generar" value="xerar agora" style="border:1px; background-color:#D7E3EA;width:140px;padding:5px;margin-top:30px;text-align:center;font-weight:bold; border-radius:8px;cursor:pointer;"></button>
          </div>
        </form><br>

<hr/>
<hr/>
<hr/>
        <form method="post" action="/realData/generate">
          <div style="width:60%;margin:auto;">
            <h3 style="padding-top:15px;">Xerador de datos reais:</h3>
            <div style="padding-top:10px;">
              <p>Records</p>
              <input style="width:50px;" name="resNum" value="1000"/>
            </div>
            <input type="submit" id="generar" value="xerar agora" style="border:1px; background-color:#D7E3EA;width:140px;padding:5px;margin-top:30px;text-align:center;font-weight:bold; border-radius:8px;cursor:pointer;"></button>
          </div>
        </form>
      </div>
    </div><!-- /#page-wrapper -->
  </div><!-- /#wrapper -->

</body>

</html>
