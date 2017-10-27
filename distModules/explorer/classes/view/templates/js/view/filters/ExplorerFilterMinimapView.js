var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterMinimapView = geozzy.filterView.extend({

  isTaxonomyFilter: true,

  // Relación de idNames con el Nombre real de la región (me lo pasa Pablo)
  names: {
    'pobra': 'A Pobra do Brollón',
    'boveda': 'Bóveda',
    'castrocaldelas': 'Castro Caldelas',
    'chantada': 'Chantada',
    'esgos': 'Esgos',
    'monforte': 'Monforte de Lemos',
    'montederramo': 'Montederramo',
    'ramuin': 'Nogueira de Ramuín',
    'peroxa': 'A Peroxa',
    'teixeira': 'A Teixeira',
    'panton': 'Pantón',
    'paradadesil': 'Parada de Sil',
    'paradela': 'Paradela',
    'xunqueira': 'Xunqueira de Espadanedo',
    'portomarin': 'Portomarín',
    'quiroga': 'Quiroga',
    'ribasdesil': 'Ribas de Sil',
    'savinao': 'Saviñao',
    'sober': 'Sober',
    'taboada': 'Taboada',
    'carballedo': 'Carballedo'
  },
  // Guardo el idName path
  idName: false,

  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      mainContainerClass: false,
      containerClass: false,
      template: geozzy.explorerComponents.filterMinimapViewTemplate,
      data: false,
      styles: {
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


    that.miniMapCreate();
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
            click: function( e, id, mapElem, textElem, elemOptions ) {
              var newData = { 'areas': {} };
              // Reseteamos la área al color por defecto
              newData.areas[that.idName] = {
                attrs: { fill: that.options.styles.background_fill }
              };
              // Coloreamos la nueva zona tras el click
              newData.areas[id] = {
                attrs: { fill: that.options.styles.selected_fill }
              };

              that.idName = id;
              $( '.minimap' ).trigger( 'update', [ { mapOptions: newData } ] );
              $( '.selectedText' ).html( that.names[that.idName] );

              that.parentExplorer.applyFilters();
            },
            mouseover: function( e, id, mapElem, textElem, elemOptions ) {
              $( '.selectedText' ).html( that.names[id] );
            },
            mouseout: function( e, id, mapElem, textElem, elemOptions ) {
              if( that.idName !== false ) {
                $( '.selectedText' ).html( that.names[that.idName] );
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
    ( function( factory ) {
      factory(jQuery, jQuery.mapael);
    }( function( $, Mapael ) {
      $.extend( true, Mapael, {
        maps :  {
          miniMapPaths : {
            width : 336,
            height : 355,
            getCoords : function(lat, lon) {
              return {"x" : lon, "y" : lat};
            },
            'elems': {
              "xunqueira" : "m 106,285 v -1 -1 l 1,-3 1,-2 1,-1 v -1 l 1,-1 h 1 v -1 l 2,-4 v -1 l 2,-3 v -1 -1 l 1,-2 -1,-2 4,4 2,1 h 1 l 1,1 h 2 2 v 1 4 l -1,2 h 1 v 1 l 1,1 v 1 1 1 1 1 1 1 h 1 1 v 5 l -2,2 -3,4 -5,2 h -1 -2 -2 l -3,1 -3,-4 -2,-6 -1,-1",
              "chantada" : "m 19,141 v -1 -1 l 1,-1 v -1 -1 h 1 v -1 l 1,-2 1,-2 v -2 -2 -1 -1 h 1 v -1 -1 -3 h 1 l -1,-2 1,-1 v -1 -1 -1 l -1,-1 v -1 l -1,-1 v -1 -2 -1 l 1,-1 v -1 l 1,-1 v -1 h 1 l 2,-1 h 1 2 l 1,1 h 1 l 1,1 v 1 1 h 1 1 v 1 1 1 l 1,1 1,1 v -1 h 1 1 l 1,1 h 1 v 1 l 1,1 v 1 l 1,1 v 1 h 1 1 1 l 1,-1 h 1 1 l 1,-1 1,-1 h 1 1 v 1 l 1,-1 v 1 l 1,-2 h 1 v -1 h 1 v 1 h 1 1 v 1 l 1,1 h -1 v 1 1 h 1 l 1,1 h 1 v 1 -1 h 1 v -1 -1 h 3 l 1,-1 h 1 1 1 1 1 1 2 1 2 1 v 1 l 1,2 v 1 h 1 l 1,1 h 1 1 1 v 1 h 1 v 1 h -1 l -1,1 -1,2 v 1 1 1 1 l 1,1 1,1 h 1 1 l 1,1 1,1 v 1 l -1,3 v 1 l -1,1 -1,1 -1,2 v 1 l -1,1 v 1 1 1 l -1,1 v 1 1 l 1,2 v 1 1 1 1 l -1,1 v 2 1 1 h 1 l 1,1 h 1 1 v 1 h 2 1 1 l 1,-1 h 1 1 1 1 1 l 1,1 1,1 h -1 v 1 1 h -1 l -1,1 -1,1 h -1 -1 l -1,1 -1,1 h -1 v 1 1 1 l -1,1 -1,1 v 1 1 l 1,1 v 1 h -1 -1 -1 l -1,-1 -1,-2 h -1 v -1 h -1 l -2,-2 h -2 v -1 h -1 v -1 -1 h -1 v -1 h -1 -1 -1 v 1 h -1 v -1 l -1,1 v 1 l -1,-1 h -1 l -2,-1 h -1 v -1 h -1 l -1,-1 h -1 -1 l -2,-1 h -1 -1 v -1 h -1 v -1 l -1,-1 v -1 l -2,-1 h -1 l -1,1 h -1 l -1,1 -1,1 h -1 -1 -1 -1 -1 l -2,-1 h -1 v -1 h -1 1 v -1 l -1,-1 v -1 h -1 v -1 l -1,-1 h -1 v 1 h -1 l -1,1 h -1 l -1,1 -1,1 h -1 v -1 h -1 l -1,-1 h -1 l -1,-1 h -1 -1 -1 -1 l -1,1 h -1 -1 l -1,-1 h -1 -1 l -1,-1 h -1 l -1,-1 h 1 1 l 1,-1 h 1 1 v -1 h 1 v -1 -1 -1 h 1 l -1,-1 v -1 l -1,-2 v -1 l -1,-1 v -3",
              "paradadesil" : "m 119,264 1,-1 h 1 v -1 -1 -1 l 1,-1 v -1 -1 h 1 v -1 l 1,-1 v -1 -1 l 1,-1 v -1 -1 h 1 v -1 h 1 l 2,-1 1,-1 h 1 v -1 h 1 v -1 l 1,-1 v -1 h 1 l 1,-1 h 1 l 1,-1 2,-1 v -1 h 1 l 1,1 2,1 1,1 1,1 1,1 v 1 h 1 l 1,1 v 1 h 1 v 1 l 2,1 h 1 1 l 1,1 1,-1 h 1 1 1 1 1 l 1,-1 h 1 1 1 1 l 1,-1 h 1 v 1 l 1,1 v 1 h 1 v 1 1 1 1 l -1,1 v 1 1 h -1 v 1 1 1 1 h -1 v 1 1 1 l -1,1 v -1 h -1 v 1 h 1 v 1 1 h -1 v 1 1 1 1 h -1 v 1 h -1 v 1 h -1 -1 -1 -1 v 1 h -1 l -2,1 v 1 h -1 v 1 h -1 v -1 h -1 v 1 1 h -1 -1 -1 l -1,1 -1,1 v 1 h -1 l -1,1 h -2 -1 v -5 l -2,-1 v -1 h -1 v -1 l -2,-1 -4,-1 -1,-1 -1,-1 -1,-1 -2,-2 -1,-1 h -2 -2 l -1,-1 h -1 l -2,-1",
              "pobra" : "m 181,148 v -1 -1 h 1 v -1 l 1,-1 v -1 -1 -1 h 1 v -1 h -1 l 1,-1 h 1 1 1 v -1 h 1 l 1,1 h 1 1 1 1 1 l 1,-1 h 1 v -1 -1 h -1 v -1 l -1,-1 v -1 -1 -1 -1 -1 h 1 1 l 1,-1 2,-2 1,-1 1,-1 v -1 -1 l 2,-2 v -1 h 1 1 l 1,-1 2,-1 h 2 v 1 h -1 l 1,1 1,2 v 1 l 1,2 -1,1 1,1 1,1 v 1 1 h -1 l 1,1 h 1 l 1,1 h 1 l 1,1 h 1 v 1 l 1,1 h 1 1 1 1 1 l 1,-1 1,-1 1,1 v -1 h 1 l 2,-1 h 1 1 v -1 h 1 1 1 v 1 l 2,1 1,1 h 1 l 1,1 v 1 1 1 h 1 l 1,1 h 1 l 1,1 1,1 v 3 1 l 2,1 h 1 l 1,2 1,1 v 1 l 1,1 v 1 2 2 l -1,1 v 1 l -2,1 v 1 h -1 v 1 l 1,2 -3,2 -1,1 v 1 l -1,1 h -1 -1 v 1 h -1 l 1,1 v 1 h 1 v 1 1 l -1,1 -2,1 v 1 h -1 v -1 l -1,-1 h -1 v -1 h -1 l -1,1 -1,1 -1,1 v 1 h -1 v 1 h -1 v 1 h -1 -1 l -1,1 -1,1 -1,1 v 1 1 l -1,1 v 1 1 h -1 v 1 l 1,1 v 1 h -1 v 1 h -1 v -1 h -1 -1 -1 -1 v -1 l -1,1 v 1 1 2 h -1 l -2,1 h -1 l -2,-1 v 3 l 3,5 v 9 1 l -1,1 1,2 h -1 -1 -1 -2 -1 v 1 h -1 l -1,1 h -1 -1 l -1,1 -3,2 -1,-1 h -1 l 1,-2 v -1 l -1,-1 h 1 v -1 h 1 l 1,-1 v -1 -1 -1 h 1 v -1 -1 l 1,-1 v -1 l 2,-3 1,-1 v -1 h -1 -1 l -1,-1 v -1 h -1 -1 v -1 h -1 v -1 h -1 v -1 h -1 l -1,-1 v -2 l 2,-2 1,-2 v -3 -1 h -1 l -1,-1 h -1 v -1 -1 l -1,-1 -1,-1 v -1 -2 -1 l 1,-1 -1,-2 -3,-4 v -2 h -2 -1 -1 -1 -1 -1 v -1 h -1 -1 -1 v -1 l -1,-1 -1,-1 1,-4 1,-2 1,-1 v -4 -1 l -2,-2",
              "castrocaldelas" : "m 182,266 v -1 -1 -1 l 1,-1 v -1 -1 -1 -1 h 1 -1 v -1 -1 h 1 v -1 h -1 v -1 h -1 v -1 h 1 -1 v -1 -1 l 1,-1 h -2 v -1 -1 h 1 v -1 h 1 v -1 -1 h 1 1 1 l 1,1 1,1 1,-1 1,1 h 1 v -1 l 1,-1 v -2 -1 l -1,-1 -1,1 h -1 -1 -1 v -1 -1 -1 h -1 v -1 -1 1 -1 h 1 l 1,-1 1,-1 v -1 h 1 l 1,-1 2,-1 h 1 v -1 h 1 l 1,-1 1,-1 2,-1 1,-1 h 1 1 v -1 h 1 l -1,-1 1,-2 2,1 h 1 1 l 2,1 h 1 l 1,1 1,1 1,2 h 1 v 1 1 l 1,1 1,1 2,2 h 1 l 1,1 v 1 h 1 1 v 1 l -1,1 v 1 1 1 h 1 1 v 1 h 1 l 1,1 h 1 v 1 1 h -1 v 1 1 h 1 l -1,1 h -1 l -1,1 v 1 l 1,1 h 1 v 1 1 h -1 v 1 1 h -1 l -1,1 h -1 -1 -1 l -1,1 -1,1 2,2 1,1 -1,1 2,3 v 2 l -5,3 h -1 l -4,1 -5,1 -3,1 -4,3 -2,2 -2,3 -1,1 v 2 l -3,1 -1,-2 h -1 v -1 l -1,-2 -3,-3 -1,-4 -1,-1 2,-2 1,-1 1,-1 -1,-1 v -1 h -1 -1 l -1,-1 h -1",
              "portomarin" : "m 69,55 v -9 l -1,-3 h 2 l -1,-5 v -2 l 1,-1 1,-1 -1,-2 v -1 -3 l 1,-2 v -1 h 3 l 2,-3 1,-1 h 4 l 4,-2 v -4 l 1,1 v -1 h 1 l 4,-2 2,-1 h 1 1 V 10 h 1 1 1 v 1 h 1 v 1 h 1 v 1 1 h 1 1 l 1,-1 1,1 v 1 h 1 l 1,1 3,-1 2,1 h 1 l 1,1 v 1 1 h 1 1 1 v 1 h 1 1 1 1 l 1,1 v 2 h 2 l 1,1 -1,3 v 2 1 h 1 l -1,1 1,2 h 1 1 v -1 h -1 1 1 v 1 l 1,1 v 1 l -1,1 v 1 1 1 2 1 1 h -1 v 1 h -1 -1 v 1 h 1 l -1,1 -1,1 h -1 -1 -1 l -1,1 v 1 l -1,1 -1,1 h -1 -1 -1 -1 l -1,1 h -1 v 1 1 1 h 1 1 l 1,-1 h 1 1 v 1 h 1 v 1 1 l -1,1 v 1 h -1 v 1 2 1 1 1 1 1 h -1 -1 -1 v -1 -1 h -1 v -1 l -1,1 h -1 v 1 l -1,1 v 1 h -1 v -1 -1 h -1 -1 v -1 h -1 v 1 h -1 -1 -1 -1 -1 v 1 -1 h -1 v -1 l -1,-1 v -1 h -1 l 2,-3 -1,-2 h -1 l -1,-1 -1,-3 h -1 -1 l -3,1 -1,2 -1,1 -3,1 -1,1 -4,-2 -3,-3 h -1 l -2,-1 h -1 -2 l -1,1",
              "peroxa" : "m 37,222 v -1 h -1 v -1 -1 -2 -1 -1 -1 -2 l 1,-1 v -1 l -1,-1 h 1 v -1 -1 l 1,-1 1,-1 1,-1 1,-1 1,-1 v -1 -1 l 1,-1 2,-1 2,-1 1,1 1,1 1,-1 h 1 l 1,1 1,1 h 1 l 1,3 h 1 l 1,1 h 1 l 1,-1 1,-1 3,-1 v -1 h 1 l 1,-2 2,-1 h 1 1 1 1 v 1 1 h 1 v 1 h 1 1 1 v 1 1 l 1,1 1,1 h -1 l -1,1 v 1 l -1,-1 v 1 -1 h -1 -1 v -1 h -1 -1 l -1,1 h -1 -1 l -1,1 v 1 l -1,1 -2,-1 h -1 l -1,1 v 1 h 1 v 1 1 -1 h 1 l 1,-1 1,1 v 1 l 1,1 1,1 1,1 h 1 l 1,1 1,-1 1,1 v 1 1 h 1 1 l 1,1 h 1 v -1 h 1 1 v -1 l 1,-1 h 1 1 v 1 h 1 1 v 1 l -1,1 -1,1 -1,1 -1,2 -2,1 v 1 h -1 v 1 l -1,1 v 1 l -1,1 h -1 l -1,1 -1,1 -1,1 v 1 l -1,1 h -1 l -1,1 h -1 v 1 l -2,2 -1,1 h -1 -1 -1 l -1,1 v -1 -1 h -1 v -1 -1 l -1,-2 h -1 v -1 l -2,-1 -1,-1 v -1 -1 -1 h -1 v -1 l -1,-2 -1,-1 -1,1 h -1 -1 -1 -1 -1 -1 -1 v -1 -1 l -1,-1 h -1 -1 l -1,1 h -1",
              "quiroga" : "m 214,214 -1,-2 1,-1 v -1 -9 l -3,-5 v -3 l 2,1 h 1 l 2,-1 h 1 v -2 -1 -1 l 1,-1 v 1 h 1 1 1 1 v 1 h 1 v -1 h 1 v -1 l -1,-1 v -1 h 1 v -1 -1 l 1,-1 v -1 -1 l 1,-1 1,-1 1,-1 h 1 1 v -1 h 1 v -1 h 1 v -1 l 1,-1 1,-1 1,-1 h 1 v 1 h 1 l 1,1 v 1 h 1 v -1 h 1 v 1 h 2 l 1,1 2,1 h 1 v 1 h 1 1 v 1 h 1 1 l 1,-1 h 1 1 l 2,-2 h 1 2 1 2 v -1 h 1 v -1 -1 h 1 v -1 -1 h 1 1 1 l 1,-1 1,-1 2,-1 v -1 h 1 v -1 l 1,-1 h 1 v -1 l 1,-1 v -1 l 1,-1 h 1 1 1 l 1,-1 h 1 1 v -1 l 1,-1 v -2 -1 h 1 v -1 -1 h 1 1 l 2,-1 h 1 l 1,-1 h 1 1 1 1 1 l 1,-1 v -1 l 1,-1 h 1 v -2 h 1 v -1 l 2,-2 h 2 1 v -1 l 1,-1 1,-1 h 2 l 1,-1 h 1 l 1,-1 h 1 1 1 v 1 1 h 1 v 1 1 l 1,1 v 1 h 2 l 1,1 h 1 1 l 1,2 h -1 v 1 1 l 1,1 v 1 h 1 v 1 h 1 v 2 l -1,3 -1,3 3,3 h -1 -1 1 v 1 1 h 1 v 1 h -1 v 1 l -1,1 v 1 1 h -1 v 1 h 1 1 1 1 v 1 1 1 l 1,1 -1,1 h -1 v 1 1 l -1,2 h -1 v 1 1 1 1 h -1 l -1,1 v 1 l -1,1 1,1 v 1 1 1 l -1,1 h -1 v 1 l -1,1 v 1 l -1,2 v 1 1 h -1 v 1 1 l -1,1 v 1 l -2,2 v 2 1 1 1 h -1 -1 -1 -1 -1 v 1 l -3,2 -1,2 -1,1 h -1 v 1 1 1 h -1 l -1,-1 h -1 l -1,1 -2,2 h -1 l -1,2 v 1 1 1 l 1,1 v 1 h 1 v 1 h -1 v 1 l -1,1 h -1 v 1 h -1 v 1 l -1,1 -1,2 -1,1 1,1 v 1 1 1 1 h -1 v 1 1 l -1,2 v 3 l -2,2 -1,-1 h -1 v 1 l -1,1 v 1 l 1,3 h -2 l -1,1 -2,5 v 1 l 1,3 v 1 l -1,1 v 1 3 h -1 v 1 l -1,1 v 1 1 l -2,-2 -2,-2 -1,-1 -1,-1 v -1 l -1,-2 v -1 l -1,-1 v -1 h -1 v -1 -1 l -1,-1 v -1 l -1,-1 v -1 l 1,-1 v -1 -1 l 1,-1 v -1 l -1,-1 v -1 -1 l 1,-1 v -1 h 1 v -1 h 2 1 v -1 l -1,-2 h -1 v -1 -1 h 1 l 1,-1 v -1 h -1 l -1,-1 v -1 -1 -1 -1 h -1 v -1 h -1 v -1 -1 -1 h 1 v -1 h 1 v -1 h -1 v -1 -1 -1 -1 -1 -1 -1 l 1,-1 h 1 v -1 -1 h -1 l -1,-1 h -1 -1 l -1,-1 h -1 l -1,-1 h -1 -1 l -1,1 h -1 -1 -1 v -1 -1 -1 -1 l 1,-1 v -1 h -1 l -1,1 h -1 -1 -1 -1 l -1,-1 v -1 -1 l -1,-1 v -1 l -1,-1 v -1 h -1 -1 v -1 h -1 -1 l -1,1 -1,1 -1,1 h -2 -1 v -1 -1 -1 h -1 -1 v 1 l 1,1 -1,1 v 1 h -1 v 1 h -1 v 1 h -1 v -1 h -1 v -1 h -1 -1 v 1 1 1 h -1 -1 -1 v 1 h -2 v 1 h -2 -1 -1 -1 -1 -1 -1 -1 l -1,1 h -1 -1",
              "ribasdesil" : "m 200,219 3,-2 1,-1 h 1 1 l 1,-1 h 1 v -1 h 1 2 1 1 1 1 1 l 1,-1 h 1 1 1 1 1 1 1 2 v -1 h 2 v -1 h 1 1 1 v -1 -1 -1 h 1 1 v 1 h 1 v 1 h 1 v -1 h 1 v -1 h 1 v -1 l 1,-1 -1,-1 v -1 h 1 1 v 1 1 1 h 1 2 l 1,-1 1,-1 1,-1 h 1 1 v 1 h 1 1 v 1 l 1,1 v 1 l 1,1 v 1 1 l 1,1 h 1 1 1 1 l 1,-1 h 1 v 1 l -1,1 v 1 1 1 1 h 1 1 1 l 1,-1 h 1 1 l 1,1 h 1 l 1,1 h 1 1 l 1,1 h 1 v 1 1 h -1 l -1,1 v 1 1 1 1 1 1 1 h 1 v 1 h -1 v 1 h -1 v 1 1 1 h 1 v 1 h 1 v 1 1 1 1 l 1,1 h 1 v 1 l -1,1 h -1 v 1 1 h 1 l 1,2 v 1 h -1 l -1,-1 -1,-1 h -1 l -1,-1 h -2 v -1 l -1,1 h -1 -2 -1 l -2,-2 -1,-2 -1,-1 -1,-1 v -1 l -2,-1 v -2 l -1,-2 -2,2 -3,-1 h -2 -1 l -1,-1 v -1 -1 l -1,-1 v -1 h -1 l -1,-2 h -1 -1 -1 -1 -1 l -1,1 -2,-1 -2,1 h -1 l -1,-1 h -4 -1 l 1,1 h -1 -2 -2 l -2,2 -2,1 v -1 h -1 l -1,-2 -1,-1 -1,-1 h -1 l -2,-1 h -1 -1 l -2,-1 -1,2 h -1 l 1,-2 v -1 h 1 v -1 -1 1 h -1 v 1 h -1 -1 v -1 l -1,-1 v -1 h 1",
              "esgos" : "m 77,289 -1,-1 v -1 -1 -1 -1 l 1,-1 1,-1 v -1 l 1,-1 h 1 1 l 1,-1 2,-1 1,-1 v -1 -1 l 1,-1 v -1 l 1,-2 v -1 -2 l -1,-2 -1,-2 v -1 -1 -1 h 2 l 1,-1 h 1 v -1 h 1 l 2,2 1,1 1,1 h 1 l 4,2 4,1 3,-4 h 1 v -1 -1 h 1 l 1,-1 h 1 l 2,-1 3,2 1,2 -1,2 v 1 1 l -2,3 v 1 l -2,4 v 1 h -1 l -1,1 v 1 l -1,1 -1,2 -1,3 v 1 1 h -1 -1 l -3,3 h -1 -2 l -3,2 -2,1 v -1 l -2,-1 h -3 -1 l -4,-1 h -2 l -1,-1 v 1 h -1 v 1 h -1 -1",
              "savinao" : "m 80,115 v -1 h 1 v -1 l 1,-1 v -1 -1 l 1,-1 v -1 -1 -1 -1 -1 l 1,-1 v -1 h 1 l 1,-1 h 1 l 1,-1 h 1 l 1,-1 h 1 1 l 1,1 h 1 1 1 1 l 1,-1 h 1 v -1 -1 -1 -1 h 1 1 l 1,-1 v -1 h -1 v -1 l -1,-1 v -1 l -1,-1 h -1 -1 v -1 -1 l -1,-1 v -1 h 1 l 1,-1 v -1 l -1,-1 v -1 -1 -1 -1 h 1 1 1 l 1,-1 h 1 v -1 l 1,-1 h 1 v 1 h 1 l 1,1 h 1 1 v 1 1 1 1 h 1 1 1 1 1 l 1,1 v -1 h 1 v 1 l 1,5 h 1 v 1 h 1 l 1,1 h 1 v 1 l 1,-1 v 1 1 h 1 v 1 1 h 1 v 1 h 1 l 1,1 3,4 1,1 L 131,101 h 1 l 2,1 1,1 4,3 1,1 1,3 h 2 l 1,1 1,1 v 1 l 1,1 v 2 l 1,2 -1,1 -1,1 v 1 1 l -1,1 v 1 l -1,1 v 1 1 h -1 v 1 h -1 v 1 l 2,2 v 1 l 1,1 2,1 v 2 1 l -1,1 -1,2 -1,1 -1,2 v 1 2 2 h -1 v -1 h -1 l 1,1 h -1 v -1 h -1 -1 -1 v -1 h -2 -2 -1 -1 l -1,1 v 1 h -1 l -1,1 v 1 l -1,1 -1,1 h -1 v 1 h -1 l -1,1 v 1 h -1 v -1 h -1 l -1,1 -1,1 -1,1 v 1 1 1 l -1,1 h -1 l -3,1 h -1 v 2 1 l 1,1 1,1 v 1 1 h -1 -1 -1 -1 -3 -1 l -1,1 v -1 h -1 v -1 h -1 -1 v 1 h -2 l -1,-1 v -1 -1 -1 -1 -1 h 1 l -1,-1 -1,-1 h -1 -1 -1 -1 -1 l -1,1 h -1 -1 -2 v -1 h -1 -1 l -1,-1 h -1 v -1 -1 -2 l 1,-1 v -1 -1 -1 -1 l -1,-2 v -1 -1 l 1,-1 v -1 -1 -1 l 1,-1 v -1 l 1,-2 1,-1 1,-1 v -1 l 1,-3 v -1 l -1,-1 -1,-1 h -1 -1 l -1,-1 -1,-1 v -1 -1 -1 -1 l 1,-2 1,-1 h 1 v -1 h -1 v -1 h -1 -1 -1 l -1,-1 h -1 v -1 l -1,-2",
              "sober" : "m 149,194 -1,1 -1,1 -1,1 -1,0 0,1 -1,0 0,-1 -1,0 0,-1 -1,0 0,1 -1,0 -1,0 0,-1 -1,0 0,1 0,1 -1,1 -2,0 0,-1 -1,0 0,1 -1,1 -1,0 0,1 -1,0 -1,0 -1,0 -1,1 0,1 -1,1 0,1 -1,0 -1,0 0,1 -1,0 -1,0 -1,0 0,1 -1,1 -1,1 0,1 -1,1 0,1 0,1 0,1 0,2 0,1 -1,1 0,1 0,1 0,1 -1,0 -1,0 -1,0 -1,0 -1,0 0,1 0,1 -1,0 -1,0 0,1 -1,0 -1,-1 -1,0 -1,0 0,1 0,1 0,2 0,1 0,1 0,1 0,1 1,1 0,1 1,1 0,1 1,0 1,0 1,0 1,0 1,1 1,0 0,1 1,1 1,0 1,0 1,1 0,1 1,1 -1,1 0,1 1,1 0,1 1,0 0,1 1,0 0,2 1,1 1,1 1,0 0,-1 1,0 2,-1 1,-1 1,0 0,-1 1,0 0,-1 1,-1 0,-1 1,0 1,-1 1,0 1,-1 2,-1 0,-1 1,0 1,1 2,1 1,1 1,1 1,1 0,1 1,0 1,1 0,1 1,0 0,1 2,1 1,0 1,0 1,1 1,-1 1,0 1,0 1,0 1,0 1,0 1,-1 1,0 1,0 1,0 1,0 1,-1 1,0 0,-1 1,0 1,-1 0,-1 1,0 1,-1 2,-1 1,0 0,-1 1,0 0,-1 1,0 1,0 1,-1 2,-1 0,-1 1,0 1,0 1,0 1,0 1,0 1,0 0,-1 0,-1 -2,-2 -2,-2 0,-5 1,-2 1,-2 -4,-1 -5,1 -2,0 -1,1 -1,0 -1,1 -1,0 -1,0 1,-1 -1,0 0,-1 -1,0 -1,0 -1,0 0,-1 0,-2 -1,0 0,-1 -1,0 -1,-1 0,-1 -1,-1 0,-1 0,-1 0,-1 -2,-3 -1,0 -1,-1 1,0 1,0 0,-1 -1,-1 0,-1 -1,0 0,-1 -1,0 0,-1 0,-1 0,-1 0,-1 0,-1 0,-1 -1,0 -1,0 0,-1 -1,0 -2,-1 -1,0 0,-1 -1,-1 z m 44,22 -1,1 0,1 0,2 -1,1 -1,0 -1,0 -1,0 -1,0 0,1 0,1 -1,0 -1,0 1,1 1,1 1,0 0,1 2,2 0,1 0,1 0,1 0,2 1,0 2,-1 1,0 0,-1 1,0 1,-1 1,-1 2,-1 -1,-1 0,-1 -1,0 -1,-2 -1,-2 1,-5 -3,-1 z",
              "taboada" : "m 29,102 1,-1 -1,-1 v -1 -1 l 1,-1 h 1 1 1 v -2 l 1,-2 -1,-1 v -1 l -1,-1 v -1 -1 -1 -1 l 1,-1 h 1 v 1 h 1 l 2,1 1,1 6,-1 2,-1 1,-1 h 1 l 3,2 3,-1 1,-2 1,-1 v -3 -5 l 3,-3 v -1 l -4,-3 3,-3 v -2 h 2 l 3,1 4,-1 2,-7 v -1 l 1,-1 h 2 1 l 2,1 h 1 l 3,3 4,2 1,-1 3,-1 1,-1 1,-2 3,-1 h 1 1 l 1,3 1,1 h 1 l 1,2 -2,3 h 1 v 1 l 1,1 v 1 h 1 v 1 -1 h 1 1 1 1 1 v -1 h 1 v 1 h 1 1 v 1 1 h 1 v 1 1 1 l 1,1 v 1 1 1 l -1,1 -1,1 h -1 l -1,-1 h -1 v -1 h -1 l -1,1 v 1 h -1 l -1,1 h -1 -1 -1 v 1 1 1 1 l 1,1 v 1 l -1,1 h -1 v 1 l 1,1 v 1 1 h 1 1 l 1,1 v 1 l 1,1 v 1 h 1 v 1 l -1,1 h -1 -1 v 1 1 1 1 h -1 l -1,1 h -1 -1 -1 -1 l -1,-1 h -1 -1 l -1,1 H 88 L 87,101 h -1 l -1,1 h -1 v 1 l -1,1 v 1 1 1 1 1 l -1,1 v 1 1 l -1,1 v 1 h -1 v 1 -1 h -1 -2 -1 -2 -1 -1 -1 -1 -1 -1 l -1,1 h -3 v 1 1 h -1 v 1 -1 h -1 l -1,-1 h -1 v -1 -1 h 1 l -1,-1 v -1 h -1 -1 v -1 h -1 v 1 h -1 l -1,2 v -1 l -1,1 v -1 h -1 -1 l -1,1 -1,1 h -1 -1 l -1,1 h -1 -1 -1 v -1 l -1,-1 v -1 l -1,-1 v -1 h -1 l -1,-1 h -1 -1 v 1 l -1,-1 -1,-1 v -1 -1 -1 h -1 -1 v -1 -1 l -1,-1 h -1 l -1,-1 h -2",
              "monforte" : "m 138,147 h 1 1 v 1 h 1 l -1,-1 h 1 v 1 h 1 v -2 -2 -1 l 1,-2 1,-1 1,-2 1,-1 1,1 h 1 2 l 1,1 1,1 h 1 1 1 l 3,1 h 1 l 1,1 1,2 v 1 l 1,2 v 1 l 2,3 h 2 l 1,1 3,1 h 1 2 3 l 2,-1 h -1 v -1 l -1,-1 1,-1 h 1 v -1 h 3 l 2,2 v 1 4 l -1,1 -1,2 -1,4 1,1 1,1 v 1 h 1 1 1 v 1 h 1 1 1 1 1 2 v 2 l 3,4 1,2 -1,1 v 1 2 1 l 1,1 1,1 v 1 1 h 1 l 1,1 h 1 v 1 3 l -1,2 -2,2 v 2 l 1,1 h 1 v 1 h 1 v 1 h 1 v 1 h 1 1 v 1 l 1,1 h 1 1 v 1 l -1,1 -2,3 v 1 l -1,1 v 1 1 h -1 v 1 1 1 l -1,1 h -1 v 1 h -1 l 1,1 v 1 l -1,2 h 1 l 1,1 h -1 v 1 l 1,1 v 1 h 1 1 v -1 h 1 v -1 1 1 h -1 v 1 l -1,2 h 1 l 1,1 h -1 v 1 h -1 -1 l -1,1 -1,-1 v -1 h -1 l -1,-2 -1,-2 1,-5 -3,-1 -1,1 v 1 2 l -1,1 h -1 -1 -1 -1 v 1 1 h -1 -1 l 1,1 1,1 h 1 v 1 l 2,2 v 1 1 1 2 h 1 l -1,1 h -1 v 1 l -1,1 -1,1 h -1 v 1 -1 -1 -1 l -2,-2 -2,-2 v -5 l 1,-2 1,-2 -4,-1 -5,1 h -2 l -1,1 h -1 l -1,1 h -1 -1 l 1,-1 h -1 v -1 h -1 -1 -1 v -1 -2 h -1 v -1 h -1 l -1,-1 v -1 l -1,-1 v -1 -1 -1 l -2,-3 h -1 l -1,-1 h 1 1 v -1 l -1,-1 v -1 h -1 v -1 h -1 v -1 -1 -1 -1 -1 -1 h -1 -1 v -1 h -1 l -2,-1 h -1 v -1 l -1,-1 -1,1 -1,1 -1,-1 -1,-2 h -1 l -1,-2 -1,-3 h -1 v -1 -1 l -1,-1 v -1 -1 -1 l 1,-2 h -1 -1 l -1,-1 h -1 -1 -1 l -2,-1 h -1 -1 v -1 -1 h -2 v -1 h -1 v -1 -1 -1 -1 h 1 l 1,-1 2,-2 1,-1 h 1 l 1,-1 h 1 v -2 l 1,-1 v -1 l 1,-1 v -1 -1 -2 -2 h -1 v -2 -1 -1 -1 -1 -1 -1 h 1",
              "teixeira" : "m 164,267 h 1 v -1 -1 h -1 v -1 h 1 v 1 l 1,-1 v -1 -1 -1 h 1 v -1 -1 -1 -1 h 1 v -1 -1 l 1,-1 v -1 -1 -1 -1 h -1 v -1 l -1,-1 v -1 -1 h 1 l 1,-1 v -1 h 1 l 1,-1 2,-1 h 1 v -1 h 1 v -1 h 1 1 l 1,-1 2,-1 v -1 h 1 1 1 1 1 1 v 1 1 h 1 v 1 1 1 h 1 1 1 l 1,-1 1,1 v 1 2 l -1,1 v 1 h -1 l -1,-1 -1,1 -1,-1 -1,-1 h -1 -1 -1 v 1 1 h -1 v 1 h -1 v 1 1 h 2 l -1,1 v 1 1 h 1 -1 v 1 h 1 v 1 h 1 v 1 h -1 v 1 1 h 1 -1 v 1 1 1 1 l -1,1 v 1 1 1 h -1 -1 l -2,2 v 1 h -1 v 1 1 1 1 1 h -1 v 1 1 h -1 -2 -2 -1 -2 v -1 -1 -1 -1 -1 -1 h -1 -1 v -1 -1 l -2,-1",
              "montederramo" : "m 127,266 1,1 2,2 1,1 1,1 1,1 4,1 2,1 v 1 h 1 v 1 l 2,1 v 5 h 1 2 l 1,-1 h 1 v -1 l 1,-1 1,-1 h 1 1 1 v -1 -1 h 1 v 1 h 1 v -1 h 1 v -1 l 2,-1 h 1 v -1 h 1 1 1 1 v -1 h 1 v -1 h 1 v -1 -1 -1 -1 l 2,1 v 1 1 h 1 1 v 1 1 1 1 1 1 h 2 1 2 2 1 v -1 -1 h 1 v -1 -1 -1 -1 -1 h 1 v -1 l 2,-2 h 1 1 1 l 1,1 h 1 1 v 1 l 1,1 -1,1 -1,1 -2,2 1,1 1,4 3,3 1,2 v 1 h 1 l 1,2 3,-1 h -1 l -1,1 v 1 h 1 v 1 l 1,2 v 1 h -1 -1 v 1 l -1,1 v 2 8 h 1 l -1,1 v 1 1 h -1 1 v 1 2 l -1,5 -1,4 h -1 -2 -1 l -1,1 h -1 l -1,-1 v 1 h -1 -1 v 1 h -1 l 1,1 1,2 v 1 h 1 l 1,1 v 1 h 1 l 1,1 1,1 1,1 v 1 1 l 1,1 1,2 v 1 l 1,1 h -1 v 1 h 1 v 1 1 h 1 v 1 1 1 1 h 1 v 1 1 h -1 v -1 l -1,-1 h -1 l -1,-1 v -1 l -1,-1 v -1 l -1,-1 h -1 v -1 l -1,1 v 2 l -1,1 v 1 l -1,1 v 1 h -1 -1 l -1,-1 h -2 v -1 h -1 l -1,-1 v -3 l -1,-1 -1,-1 h -1 l -1,-1 -1,-1 h -1 l -3,-6 v -2 -1 -1 l -1,-2 v -1 h -1 -2 l -1,-1 h -3 v -3 l -2,-1 -2,-1 -2,-2 -4,-2 v -2 -3 l -1,-2 h -2 -3 l -1,-4 -2,-8 2,-5 v -1 -1 -1 h -1 l -2,1 h -2 -1 v -1 -1 h -1 l -3,3 -1,1 v -5 h -1 -1 v -1 -1 -1 -1 -1 -1 -1 l -1,-1 v -1 h -1 l 1,-2 v -4 -1",
              "ramuin" : "m 61,238 h 1 1 l 1,-1 2,-2 v -1 h 1 l 1,-1 h 1 l 1,-1 v -1 l 1,-1 1,-1 1,-1 h 1 l 1,-1 v -1 l 1,-1 v -1 h 1 v -1 l 2,-1 1,-2 1,-1 1,-1 1,-1 v -1 h 1 1 l 1,1 h 1 1 1 1 v 1 l 1,1 1,1 1,1 v 1 2 1 l 1,2 1,1 2,1 1,1 h 1 l 2,1 h 1 l 1,1 h 2 l 1,-1 h 1 1 l 1,1 v 1 l 1,1 v 1 h 1 1 1 1 l 1,1 h 1 v 1 l 1,1 h 1 1 l 1,1 v 1 l 1,1 -1,1 v 1 l 1,1 v 1 h 1 v 1 h 1 v 2 l 1,1 1,1 v 1 1 l -1,1 v 1 1 l -1,1 v 1 h -1 v 1 1 l -1,1 v 1 1 1 h -1 l -1,1 -4,-4 -3,-2 -2,1 h -1 l -1,1 h -1 v 1 1 h -1 l -3,4 -4,-1 -4,-2 h -1 l -1,-1 -1,-1 -2,-2 h -1 v 1 h -1 l -1,1 h -2 l -2,-1 h -1 -2 -1 -2 -1 -1 l -2,1 h -2 l -1,1 h -1 -1 -1 l -1,1 v -1 -1 l -1,-1 v -1 l -1,-2 -2,-1 v -1 l -1,-1 v -1 l 1,-1 h 1 l 3,-5 h 2 1 1 l 1,1 h 1 -1 1 v -1 -1 l -1,-1 v -1 l -1,-1 -1,1 h -1 -1 -1 l -1,-1 v -2 l -1,-1 h -1 l -1,-1 h -1 v -1",
              "boveda" : "m 143,110 1,-2 2,-1 2,-1 2,-1 1,-1 h 2 l 4,-3 h 1 l 1,-1 h 1 l 1,-1 2,1 L 164,101 h 1 l 1,1 2,1 1,1 1,1 v 1 h 1 1 l 1,1 1,1 h 2 1 l 1,1 2,1 h 1 l 1,1 h 1 l 1,1 1,1 1,1 1,1 v 1 h 1 v 1 1 l 1,2 h -1 l -1,1 v 1 l -1,1 v 1 1 l 1,2 1,1 1,1 h 1 1 3 v 1 1 1 1 1 l 1,1 v 1 h 1 v 1 1 h -1 l -1,1 h -1 -1 -1 -1 -1 l -1,-1 h -1 v 1 h -1 -1 -1 l -1,1 h 1 v 1 h -1 v 1 1 1 l -1,1 v 1 h -1 v 1 1 h -3 v 1 h -1 l -1,1 1,1 v 1 h 1 l -2,1 h -3 -2 -1 l -3,-1 -1,-1 h -2 l -2,-3 v -1 l -1,-2 v -1 l -1,-2 -1,-1 h -1 l -3,-1 h -1 -1 -1 l -1,-1 -1,-1 h -2 -1 l -1,-1 v -1 -2 l -2,-1 -1,-1 v -1 l -2,-2 v -1 h 1 v -1 h 1 v -1 -1 l 1,-1 v -1 l 1,-1 v -1 -1 l 1,-1 1,-1 -1,-2 v -2 l -1,-1 v -1 l -1,-1 -1,-1",
              "carballedo" : "m 10,156 2,-1 1,-1 1,-1 2,-3 v -3 -1 -1 h 1 v -1 h 1 v -1 l 1,-1 v -1 3 l 1,1 v 1 l 1,2 v 1 l 1,1 h -1 v 1 1 1 h -1 v 1 h -1 -1 l -1,1 h -1 -1 l 1,1 h 1 l 1,1 h 1 1 l 1,1 h 1 1 l 1,-1 h 1 1 1 1 l 1,1 h 1 l 1,1 h 1 v 1 h 1 l 1,-1 1,-1 h 1 l 1,-1 h 1 v -1 h 1 l 1,1 v 1 h 1 v 1 l 1,1 v 1 h -1 1 v 1 h 1 l 2,1 h 1 1 1 1 1 l 1,-1 1,-1 h 1 l 1,-1 h 1 l 2,1 v 1 l 1,1 v 1 h 1 v 1 h 1 1 l 2,1 h 1 1 l 1,1 h 1 v 1 h 1 l 2,1 h 1 l 1,1 v -1 l 1,-1 v 1 h 1 v -1 h 1 1 1 v 1 h 1 v 1 1 h 1 v 1 h 2 l 2,2 h 1 v 1 h 1 l 1,2 1,1 h 1 1 1 v 1 l -1,1 v 1 l -1,1 -1,2 v 1 h -1 v 1 h 1 v 1 l 1,1 v 2 1 1 h 1 v 1 l 1,1 1,1 v 1 l 1,3 v 1 1 h 1 v 1 l -1,1 v 1 l -1,1 h -1 -1 l -1,1 -1,1 -1,1 v 1 1 1 1 l -1,1 -1,1 h -1 v 1 1 h -1 -1 v -1 h -1 -1 l -1,1 v 1 h -1 -1 v 1 h -1 l -1,-1 h -1 -1 v -1 -1 l -1,-1 -1,1 -1,-1 h -1 l -1,-1 -1,-1 -1,-1 v -1 l -1,-1 -1,1 h -1 v 1 -1 -1 h -1 v -1 l 1,-1 h 1 l 2,1 1,-1 v -1 l 1,-1 h 1 1 l 1,-1 h 1 1 v 1 h 1 1 v 1 -1 l 1,1 v -1 l 1,-1 h 1 l -1,-1 -1,-1 v -1 -1 h -1 -1 -1 v -1 h -1 v -1 -1 h -1 -1 -1 -1 l -2,1 -1,2 h -1 v 1 l -3,1 -1,1 -1,1 h -1 l -1,-1 h -1 l -1,-3 h -1 l -1,-1 -1,-1 h -1 l -1,1 -1,-1 -1,-1 -2,1 -2,1 -1,-1 v -2 l -1,-1 h -1 -1 v -1 h -1 l -1,-1 -2,-1 h -1 -1 v 1 h -1 l -1,1 -2,-1 v -1 -1 l 1,-1 v -1 h -1 -1 -1 -1 -1 v 1 h -1 l -1,1 v 1 l -1,-1 h -1 -1 -1 -1 -1 v -1 l 1,-1 1,-1 1,-1 1,-3 h -3 v -1 h -1 l -4,-1 h 2 l 1,-1 h 1 l 2,-2 1,1 1,-1 -1,-2 h 1 l 1,-1 h 1 v -1 h 1 l 1,1 v -1 1 -2 -4 l -1,-1 h -1 l -2,-1 h -2 l -2,-1 -1,-1 -1,-2 h -1 v -2 l -1,-1 -1,-2 v -2 l -1,-1 h -1",
              "panton" : "m 83,216 v -1 -1 h 1 l 1,-1 1,-1 v -1 -1 -1 -1 l 1,-1 1,-1 1,-1 h 1 1 l 1,-1 v -1 l 1,-1 v -1 h -1 v -1 -1 l -1,-3 v -1 l -1,-1 -1,-1 v -1 h -1 v -1 -1 -2 l -1,-1 v -1 h -1 v -1 h 1 v -1 l 1,-2 1,-1 v -1 l 1,-1 v -1 -1 l -1,-1 v -1 -1 l 1,-1 1,-1 v -1 -1 -1 h 1 l 1,-1 1,-1 h 1 1 l 1,-1 1,-1 h 1 v 1 1 1 l 1,1 h 2 v -1 h 1 1 v 1 h 1 v 1 l 1,-1 h 1 3 1 1 1 1 v -1 -1 l -1,-1 -1,-1 v -1 -2 h 1 l 3,-1 h 1 l 1,-1 v -1 -1 -1 l 1,-1 1,-1 1,-1 h 1 v 1 h 1 v -1 l 1,-1 h 1 v -1 h 1 l 1,-1 1,-1 v -1 l 1,-1 h 1 v -1 l 1,-1 h 1 1 2 2 v 1 h 1 -1 v 1 1 1 1 1 1 2 h 1 v 2 2 1 1 l -1,1 v 1 l -1,1 v 2 h -1 l -1,1 h -1 l -1,1 -2,2 -1,1 h -1 v 1 1 1 1 h 1 v 1 h 2 v 1 1 h 1 1 l 2,1 h 1 1 1 l 1,1 h 1 1 l -1,2 v 1 1 1 l 1,1 v 1 1 h 1 l 1,3 1,2 h 1 l 1,2 1,1 -1,1 v 1 -1 h -1 v 1 -1 1 h -1 v -1 h -1 v -1 h -1 v 1 h -1 -1 v -1 h -1 v 1 1 l -1,1 h -2 v -1 h -1 v 1 l -1,1 h -1 v 1 h -1 -1 -1 l -1,1 v 1 l -1,1 v 1 h -1 -1 v 1 h -1 -1 -1 v 1 l -1,1 -1,1 v 1 l -1,1 v 1 1 1 2 1 l -1,1 v 1 1 1 h -1 -1 -1 -1 -1 v 1 1 h -1 -1 v 1 h -1 l -1,-1 h -1 -1 v 1 1 2 1 1 1 1 h -1 -1 l -1,1 h -2 l -1,-1 h -1 l -2,-1 h -1 l -1,-1 -2,-1 -1,-1 -1,-2 v -1 -2 -1 l -1,-1 -1,-1 -1,-1 v -1 h -1 -1 -1 -1 l -1,-1 h -1 -1",
              "paradela" : "m 107,77 1,-1 1,-1 v -1 -1 -1 l -1,-1 v -1 -1 -1 -1 l 1,-1 v -1 h 1 l 1,-1 v 1 h 1 v 1 1 h 1 1 1 v -1 -1 -1 -1 -1 -2 -1 h 1 v -1 l 1,-1 v -1 -1 h -1 v -1 h -1 -1 l -1,1 h -1 -1 v -1 -1 -1 h 1 l 1,-1 h 1 1 1 1 l 1,-1 1,-1 v -1 l 1,-1 h 1 1 1 l 1,-1 1,-1 h 1 l 1,-1 1,1 3,1 1,-1 3,1 1,2 1,1 2,-1 3,-1 2,-1 1,1 h 1 l 2,1 h 4 l 4,-1 h 1 l 1,-1 h 1 1 l 1,-1 -1,4 v 1 1 h -1 v 1 l -2,-1 h -1 v 2 1 l -1,2 -1,1 v 1 l -1,1 h -1 v 1 l 1,1 h 1 l 1,1 h 1 1 1 3 v 1 1 1 h -1 v 1 l -1,1 1,1 h -1 v 1 h 1 v 1 h -1 v 1 l -1,1 v 1 h -1 v 1 l -8,8 1,1 1,1 4,1 2,3 v 1 l 2,1 1,1 1,2 v 1 3 1 1 l -1,1 h -1 L 158,101 h -1 l -4,3 h -2 l -1,1 -2,1 -2,1 -2,1 -1,2 h -2 l -1,-3 -1,-1 -4,-3 -1,-1 -2,-1 h -1 l -2,-1 -1,-1 -3,-4 -1,-1 h -1 v -1 h -1 v -1 -1 h -1 v -1 -1 l -1,1 v -1 h -1 l -1,-1 h -1 v -1 h -1 l -1,-5 v -1 h -1 v 1 l -1,-1 h -1 -1 -1 -1 -1 v -1 -1 -1 -1 h -1"
            }
          }
        }
      } );
      return Mapael;
    } ) );
  },

  reset: function() {
    console.log('COMBO')
    var that = this;
    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
    $select = $( that.options.mainContainerClass + ' ' + containerClassDots + ' select' );

    $select.val( "*" );

    that.selectedTerms = false;
  }

});
