<?php
Cogumelo::load('coreView/View.php');



class TestRouteView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

  }

  /**
   * Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {
    return true;
  }
/*

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
  }*/

  public function testRouteGraph() {
    rextRoutes::autoIncludes();

/*
      function CpChart\Chart\imaimagecreatetruecolor = imaimagecreatetruecolor;
      $myData = new pData();
      $myData->addPoints([0, 1, 1, 2, 3, 5, 8, 13]);
      $myImage = new pImage(500, 300, $myData);
      $myImage->setGraphArea(25,25, 475,275);
      $myImage->drawScale();
*/

  }

  public function routeConvert() {
    rextRoutes::autoIncludes();
/*


    try {
      $polygon = geoPHP::load( file_get_contents($filePath) , array_pop( $fnSplited ) );

      $cent = $polygon->getCentroid();


      $centro = json_encode( [ $cent->y(), $cent->x() ]);
      $puntos = json_encode( $this->extractPoints( $polygon ));
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }
*/

    rextRoutes::load('controller/RoutesController.php');
    $routesControl = new RoutesController();
    $filePath = '/home/pblanco/Descargas/porto.gpx';
    $rutaJSON = json_encode(    $routesControl->getRoute($filePath, 10) );


    ?>
      <!DOCTYPE html>
      <html>
        <head>
          <style type="text/css">
            html, body { height: 100%; margin: 0; padding: 0; }
            #map { height: 100%; }
          </style>

          <script src="https://code.jquery.com/jquery-1.12.3.min.js"  ></script>
          <script src="/vendor/bower/dygraphs/dygraph-combined.js"  ></script>

        </head>
        <body>
          <div id="map"></div>
          <div id="graph" style="position:absolute;width:400px;height:150px;bottom:30px;left:30px;"></div>
          <script src="https://maps.googleapis.com/maps/api/js?callback=initMap"  async defer></script>


          <script type="text/javascript">

            var route = JSON.parse('<?php echo $rutaJSON;?>');


            var map;
            var trackCircle = false;
            var grafico = false;
            var isTrackHover = false;

            function findPoint(lat, lng) {

              var lessDistance = false;
              var lessDistanceElementId = false;

              $.each( route.trackPoints , function(i,e){

                var currentDistance =  Math.pow( e[0] - lat, 2 ) + Math.pow( e[1] - lng, 2 )  ;


                if( lessDistance == false || lessDistance > currentDistance ) {
                  lessDistance = currentDistance;
                  lessDistanceElementId = i ;
                }


              });


              if( lessDistanceElementId ) {
                hoverRecorrido( lessDistanceElementId );
              }
            }


            function hoverRecorrido( id ) {
              //grafico.setSelection(id);
              grafico.setSelection(id) ;

              if( trackCircle ) {

                trackCircle.setMap(null);
              }
              var scale = Math.pow(2, map.getZoom());



              trackCircle = new google.maps.Circle({
                  center: {lat: route.trackPoints[id][0] , lng: route.trackPoints[id][1]},
                  radius: 850000/scale,
                  strokeWeight:0,
                  fillColor: "#0000FF",
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
                center: {lat: route.centroid[0], lng: route.centroid[1]},
                zoom: 13
              });


              var recorrido = [ ];

              $.each(route.trackPoints, function(i,e){
              /*  var marker = new google.maps.Marker({
                    position: new google.maps.LatLng( e[0], e[1] ),
                    map: map,
                    title: 'lat:' + e[0] + 'lon' + e[1] + 'altitude: ' + e[2]
                });*/
                recorrido.push({id:i, lat: e[0], lng:e[1] });


              });

              //cogumelo.log(recorrido)


              var recorridoPolylineBK2 = new google.maps.Polyline({
                path: recorrido,
                geodesic: true,
                strokeOpacity: 0,
                strokeWeight: 20
              });

              var recorridoPolylineBK1 = new google.maps.Polyline({
                path: recorrido,
                geodesic: true,
                strokeOpacity: 0,
                strokeWeight: 10
              });


              var recorridoPolyline = new google.maps.Polyline({
                path: recorrido,
                geodesic: true,
                strokeColor: '#000',
                strokeOpacity: 1.0,
                strokeWeight: 3
              });

              recorridoPolyline.setMap(map);
              recorridoPolylineBK1.setMap(map)
              recorridoPolylineBK2.setMap(map)

              recorridoPolylineBK2.addListener('mouseover', function(ev){
                findPoint(ev.latLng.lat(), ev.latLng.lng());
                isTrackHover = true;
              });
              recorridoPolylineBK1.addListener('mouseover', function(ev){
                findPoint(ev.latLng.lat(), ev.latLng.lng());
                isTrackHover = true;
              });
              recorridoPolyline.addListener('mouseover', function(ev){
                findPoint(ev.latLng.lat(), ev.latLng.lng());
                isTrackHover = true;
              });

              recorridoPolylineBK2.addListener('mouseout', function(ev){
                outRecorrido();
              });
              recorridoPolylineBK1.addListener('mouseout', function(ev){
                outRecorrido();
              });
              recorridoPolyline.addListener('mouseout', function(ev){
                outRecorrido();
              });



              var chartString = "step,Altitude\n";
              $.each( route.trackPoints, function(i,e){
                //if( typeof e[2] == 'undefined' ) {
                  chartString += i + "," + e[2] + "\n";
                //}
                //else {
                  //chartString += i + ",0\n";
                //}

              });
















              grafico = new Dygraph( document.getElementById("graph"),
                chartString
              );




              grafico.updateOptions( {
                annotationMouseOverHandler: function(annotation, point, dygraph, event) {
                    alert('')
                }
              });

              $("#graph").mousemove(function(e) {
                  var seleccionado = grafico.getSelection();

                  hoverRecorrido( seleccionado )

              }).mouseleave(function(e) {
                  var seleccionada = grafico.getSelection();
                  outRecorrido();


              });

            }


          </script>

        </body>
      </html>

    <?php





  }

}
