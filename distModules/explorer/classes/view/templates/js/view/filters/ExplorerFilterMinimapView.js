var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterMinimapView = geozzy.filterView.extend({

  isTaxonomyFilter: true,
  minimapExist: false,
  currentIdName: false,

  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      mainContainerClass: false,
      containerClass: false,
      resetButtonText: 'All',
      template: geozzy.explorerComponents.filterMinimapViewTemplate,
      data: false,
      styles: {
        width : 336,
        height : 355,
        stroke: '#bac0af', // appVars.less -> @gzzColor-bck-1
        background_fill: '#eaede4', // appVars.less -> @gzzColor-bck-3
        selected_fill: '#63944e' // appVars.less -> @gzzColor-green
      }
    };

    that.options = $.extend(true, {}, options, opts);


    that.template = _.template( that.options.template );

  },

  filterAction: function( model ) {
    var ret = false;

    if( this.selectedTerms != false ) {

      var terms =  model.get('terms');
      if( typeof terms != "undefined") {
        var diff = $( terms ).not( this.selectedTerms );
        //console.log(diff.length, terms.length)
        ret = (diff.length != terms.length );
      }
    }
    else {
      ret = true;
    }

    return ret;
  },

  render: function() {
    var that = this;

    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');

    var filterHtml = that.template({ });

    // Print filter html into div
    if( !$(  that.options.mainContainerClass+' .' +that.options.containerClass ).length ) {
      $( that.options.mainContainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
    }
    else {
      $( that.options.mainContainerClass+' ' + containerClassDots ).html( filterHtml );
    }


    if( that.minimapExist == false ) {
      that.miniMapCreate();
      that.minimapExist = true;
    }


    $( that.options.mainContainerClass+' .' +that.options.containerClass ).find( '.minimap' ).mapael( {
      map: {
        name: 'miniMapPaths',
        zoom: {
          enabled: false
        },
        defaultArea : {
          attrs: {
            fill: that.options.styles.background_fill,
            stroke: that.options.styles.stroke
          },
          attrsHover: {
            fill: that.options.styles.selected_fill,
            animDuration: 0
          },
          eventHandlers: {
            click: function( e, idName, mapElem, textElem, elemOptions ) {

              var newData = { 'areas': {} };
              // Reseteamos la área al color por defecto
              newData.areas[that.currentIdName] = {
                attrs: { fill: that.options.styles.background_fill }
              };
              // Coloreamos la nueva zona tras el click
              newData.areas[idName] = {
                attrs: { fill: that.options.styles.selected_fill }
              };

              that.currentIdName = idName;

              that.selectedTerms = [ that.getIdByIdName(idName) ];

              $( '.minimap' ).trigger( 'update', [ { mapOptions: newData } ] );
              $( '.selectedText' ).html( that.getNameByIdName( idName ));

              that.parentExplorer.applyFilters();
            },
            mouseover: function( e, idName, mapElem, textElem, elemOptions ) {

              $( '.selectedText' ).html( that.getNameByIdName( idName ) );
            },
            mouseout: function( e, idName, mapElem, textElem, elemOptions ) {
              if( that.currentIdName !== false ) {
                $( '.selectedText' ).html( that.getNameByIdName( that.currentIdName ) );
              }
              else {
                $( '.selectedText' ).html('');
              }
            }
          }
        }
      }
    } );

    $( that.options.mainContainerClass+' .' +that.options.containerClass ).find( '.boxMinimap' ).hide();

    // TRIGER CAMBIO DE ESTADO
    //that.selectedTerms = [ parseInt( valor ) ];

  },

  miniMapCreate: function() {
    var that = this;

    ( function( factory ) {
      factory(jQuery, jQuery.mapael);
    }( function( $, Mapael ) {
      $.extend( true, Mapael, {
        maps :  {
          miniMapPaths : {
            width : that.options.width,
            height : that.options.height,
            getCoords : function(lat, lon) {
              return {"x" : lon, "y" : lat};
            },
            'elems': that.collectionToMapaelObject()
          }
        }
      } );
      return Mapael;
    } ) );
  },

  reset: function() {
    var that = this;



    that.selectedTerms = false;
  },



  getNameByIdName: function( idName ) {
    var that = this;
    var ret = false;

    if( typeof that.options.data.where({idName: idName })[0] != 'undefined') {
      ret = that.options.data.where({idName: idName })[0].get('name') ;

    }

    return ret;
  },

  getIdByIdName: function( idName ) {
    var that = this;
    var ret = false;

    if( typeof that.options.data.where({idName: idName })[0] != 'undefined') {
      ret = that.options.data.where({idName: idName })[0].get('id') ;

    }

    return ret;
  },

  collectionToMapaelObject: function() {
    var that = this;
    var mapaelDataObject = {};

    if( that.options.data != false ) {
      that.options.data.each( function(e,i){
        try {
          eval( 'mapaelDataObject.'+ e.get('idName') + ' = "' + e.get('geom') + '";' );
        } catch(err) {
          console.log( 'Problema con idName de concello:', e.get('idName') );
        }
      });
    }

    //console.log('MAPAEL en filtro', mapaelDataObject );

    return mapaelDataObject;
  },



});
