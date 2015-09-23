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
  <div id="filters" style="width:400px;height:150px;position:absolute;right:10px;top:410px;background-color:white;"></div>
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

















    geozzy.filterList = geozzy.filter.extend({

      template: _.template("<select><option>OP1</opton><option>OP2</opton></select>"),

      filterAction: function( model ) {

        var terms =  model.get('terms');

        var diff = $( terms ).not( this.data );
        return (diff.length != terms.length );

      },

      render: function() {

        var that = this;

        if( !$( '#' + this.options.DOMContainer+' #' + this.options.DOMId ).length ) {
          $( '#' + this.options.DOMContainer).append( '<div id='+ this.options.DOMId +'>' + this.template() + '</div>' );
        }


        $('#'+this.options.DOMId+' select').bind('change', function() {

          that.data = [10,15];
          that.parentExplorer.applyFilters();
        });

      }

    });








    var explorer = new geozzy.explorer({debug:true});

    var filtro1 = new geozzy.filterList({DOMContainer: 'filters', DOMId: 'filtro1'});
    filtro1.data = [14,10,25,37];
    var filtro2 = new geozzy.filterList({DOMContainer: 'filters', DOMId: 'filtro2'});
    filtro2.data = [19,47,20,30,15,16];


    var listaActiva = new geozzy.explorerDisplay.activeList();
    var mapa = new geozzy.explorerDisplay.map();
    explorer.addFilter( filtro1 );
    //explorer.addFilter( filtro2 );
    explorer.addDisplay( 'activeList', listaActiva );
    explorer.addDisplay( 'map', mapa );

    explorer.exec();

  });

</script>
{/literal}
</body>
</html>
<!-- /portada.tpl en app de Geozzy -->
