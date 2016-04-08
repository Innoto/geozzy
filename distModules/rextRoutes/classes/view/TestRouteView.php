<?php
Cogumelo::load('coreView/View.php');



class TestRouteView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

  }

  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {
    return true;
  }


  public function extractPoints( $geom ) {
    $points = array();

    foreach( $geom->getComponents() as $comp ) {
      if( $comp->getGeomType() == 'LineString' ){
        //$points = array_merge( $points , $this->extractPoints( $comp ) );
      }
      else
      if( $comp->getGeomType() == 'Point' ) {

        if( $comp->z() ) {
          $points[] = [ $comp->y(), $comp->x(), $comp->z() ];
        }
        else {
          $points[] = [ $comp->y(), $comp->x(), false ];
        }

        //array_push( $points , get_class_methods($comp) );
        //$points[] = [$comp->getX() , $comp->getY()];
      }
    }

    return $points;
  }

  public function routeConvert() {
    rextRoutes::autoIncludes();

    //var_dump( filetype('/home/pblanco/Descargas/Felechosa_final.kml') );

    $filePath = '/home/pblanco/Descargas/Felechosa_final.gpx';

    $fnSplited = explode( '.', $filePath );
    /*array_pop( $fnSplited )*/

    try {
      $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );
      /*echo "<pre>";
      var_dump( $polygon->getGeomType() );
      echo "<br>--------------------<br>";
      var_dump( json_encode( $this->extractPoints( $polygon )) );*/
      $cent = $polygon->getCentroid();

      $centro = json_encode( [ $cent->y(), $cent->x() ]);
      $puntos = json_encode( $this->extractPoints( $polygon ));
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }


    ?>
      <!DOCTYPE html>
      <html>
        <head>
          <style type="text/css">
            html, body { height: 100%; margin: 0; padding: 0; }
            #map { height: 100%; }
          </style>

          <script src="https://code.jquery.com/jquery-1.12.3.min.js"  ></script>
        </head>
        <body>
          <div id="map"></div>

          <script src="https://maps.googleapis.com/maps/api/js?callback=initMap"  async defer></script>


          <script type="text/javascript">

            var puntos = <?php echo $puntos;?>;
            var centro = <?php echo $centro;?>;
            var map;
            var recorrido = [ ];
            var trackCircle = false;

            function findPoint(lat, lng) {


              $.each(recorrido, function(i,e){

                if( e.lat.toFixed(4) == lat.toFixed(4) && e.lng.toFixed(4) == lng.toFixed(4) ) {
                  console.log(lat, e.lat , lng, e.lng)
                  hoverRecorrido(e.id)
//                  return false;
                }
              });
            }


            function hoverRecorrido( id ) {
              console.log('altitude',puntos[id][2] + ' M' )

              if( trackCircle ) {

                trackCircle.setMap(null);
              }
              var scale = Math.pow(2, map.getZoom());



              trackCircle = new google.maps.Circle({
                  center: {lat: puntos[id][0] , lng: puntos[id][1]},
                  radius: 900000/scale,
                  strokeWeight:0,
                  fillColor: "#FF0000",
                  fillOpacity: 1,
                  map: map
              });
            }

            function outRecorrido( ) {

              if( trackCircle ) {
                trackCircle.setMap(null);
              }

            }



            function initMap() {
              map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: centro[0], lng: centro[1]},
                zoom: 13
              });



              $.each(puntos, function(i,e){
              /*  var marker = new google.maps.Marker({
                    position: new google.maps.LatLng( e[0], e[1] ),
                    map: map,
                    title: 'lat:' + e[0] + 'lon' + e[1] + 'altitude: ' + e[2]
                });*/
                recorrido.push({id:i, lat: e[0], lng:e[1] });


              });

              //console.log(recorrido)


              var recorridoPolylineBK = new google.maps.Polyline({
                path: recorrido,
                geodesic: true,
                strokeOpacity: 0,
                strokeWeight: 50
              });


              var recorridoPolyline = new google.maps.Polyline({
                path: recorrido,
                geodesic: true,
                strokeColor: '#000',
                strokeOpacity: 1.0,
                strokeWeight: 3
              });

              recorridoPolyline.setMap(map);
              recorridoPolylineBK.setMap(map)

              recorridoPolylineBK.addListener('mouseover', function(ev){
                findPoint(ev.latLng.lat(), ev.latLng.lng())
              });
/*
              recorridoPolylineBK.addListener('mouseleave', function(ev){
                outRecorrido();
              });
*/




            }


          </script>

        </body>
      </html>

    <?





  }

}
