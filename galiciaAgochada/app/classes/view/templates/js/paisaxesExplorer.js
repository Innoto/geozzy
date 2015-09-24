

    var filterList = geozzy.filter.extend({

      template: _.template("<select><option value='*'>Todos</opton><option>10</opton><option>15</opton><option>16</opton><option>17</opton></select>"),

      filterAction: function( model ) {
        var ret = false;

        if( this.selectedData != false ) {

          var terms =  model.get('terms');

          var diff = $( terms ).not( this.selectedData );

          //console.log(diff.length, terms.length)
          ret = (diff.length != terms.length );
        }
        else {
          ret = true;
        }

        return ret;
      },

      render: function() {

        var that = this;

        if( !$(  this.options.containerQueryDiv+' #' + this.options.DOMId ).length ) {
          $( this.options.containerQueryDiv).append( '<div id='+ this.options.DOMId +'>' + this.template() + '</div>' );
        }


        $('#'+this.options.DOMId+' select').bind('change', function(el) {
          var val = $(el.target).val();
          if( val == '*' ) {
            that.selectedData = false;
          }
          else {
            //that.selectedData = false;
            that.selectedData = [ parseInt( $(el.target).val() ) ];
          }

          that.parentExplorer.applyFilters();
        });

      }

    });


    $(document).ready(function(){


      // gmaps init
      var mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        zoom: 8
      };


      var resourceMap = new google.maps.Map( document.getElementById('explorerMap'), mapOptions);

      var explorer = new geozzy.explorer({debug:false});

      var filtro1 = new filterList({containerQueryDiv: '.explorer-container-filter', DOMId: 'filtro1'});
      var filtro2 = new filterList({containerQueryDiv: '.explorer-container-filter', DOMId: 'filtro2'});
      var filtro3 = new filterList({containerQueryDiv: '.explorer-container-filter', DOMId: 'filtro3'});
      var filtro4 = new filterList({containerQueryDiv: '.explorer-container-filter', DOMId: 'filtro4'});
      //filtro2.data = false;


      var listaActiva = new geozzy.explorerDisplay.activeList();
      var mapa = new geozzy.explorerDisplay.map();

      mapa.setMap( resourceMap );

      explorer.addFilter( filtro1 );
      explorer.addFilter( filtro2 );
      explorer.addFilter( filtro3 );
      explorer.addFilter( filtro4 );

      //explorer.addDisplay( 'activeList', listaActiva );
      explorer.addDisplay( 'map', mapa );

      explorer.exec();


    });
