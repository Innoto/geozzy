var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterMinimapView = geozzy.filterView.extend({

  isTaxonomyFilter: true,
  minimapExist: false,
  currentIdName: false,

  events: {
    'mouseenter' : 'mouseenterShowBoxMinimap',
    'mouseleave' : 'mouseleaveHideBoxMinimap',
    'click' : 'showBoxMinimap',
    'click .filterResetMinimap' : 'evReset'
  },

  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      elSummaryContainer:false,
      iconHtml: '<i class="fas fa-2x fa-globe-americas" aria-hidden="true"></i>',
      resetButtonText: __('All'),
      template: geozzy.explorerComponents.filterMinimapViewTemplate,
      data: false,
      openWithHover: true,
      styles: {
        width : 336,
        height : 355,
        stroke: '#bac0af', // appVars.scss -> @gzzColor-bck-1
        background_fill: '#eaede4', // appVars.scss -> @gzzColor-bck-3
        selected_fill: '#63944e' // appVars.scss -> @gzzColor-green
      }
    };

    that.options = $.extend(true, {}, options, opts);
    that.template = _.template( that.options.template );
    that.$elSummaryContainer = $(that.options.elSummaryContainer);
  },

  filterAction: function( model ) {
    var ret = false;

    if( this.selectedTerms != false ) {

      var terms =  model.get('terms');
      if( typeof terms != "undefined") {
        var diff = $( terms ).not( this.selectedTerms );
        //cogumelo.log(diff.length, terms.length);
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


    $(window).on('click', function(ev){
      if(
        !(

          $(ev.target).hasClass(that.options.containerClass ) ||
          $(ev.target).parent().hasClass(that.options.containerClass ) ||
          $(ev.target).parent().parent().hasClass(that.options.containerClass ) ||
          $(ev.target).parent().parent().parent().hasClass(that.options.containerClass )
        )
       ){
        that.hideBoxMinimap();
      }

    });

    var filterHtml = that.template( { iconHtml : that.options.iconHtml, resetButtonText : that.options.resetButtonText } );
    that.$el.html( filterHtml );

    if( that.minimapExist == false ) {
      that.miniMapCreate();
      that.minimapExist = true;
    }


    that.$el.find( '.minimap' ).mapael( {
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

              that.$el.find( '.minimap' ).trigger( 'update', [ { mapOptions: newData } ] );
              that.$el.find( '.selectedText' ).html( that.getNameByIdName( idName ));

              that.parentExplorer.applyFilters();

              //  Ocultamos el mapa tras el click
              that.hideBoxMinimap();
            },
            mouseover: function( e, idName, mapElem, textElem, elemOptions ) {
              that.$el.find( '.selectedText' ).html( that.getNameByIdName( idName ) );
            },
            mouseout: function( e, idName, mapElem, textElem, elemOptions ) {
              if( that.currentIdName !== false ) {
                that.$el.find( '.selectedText' ).html( that.getNameByIdName( that.currentIdName ) );
              }
              else {
                that.$el.find( '.selectedText' ).html('');
              }
            }
          }
        }
      }
    } );

    //  Ocultamos el mapa tras el render
    that.hideBoxMinimap();

    //that.$el = $( that.options.containerClass );
    that.delegateEvents();

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
            width : that.options.styles.width,
            height : that.options.styles.height,
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

  mouseenterShowBoxMinimap: function(ev) {
    var that = this;

    if( that.options.openWithHover == true ) {
      that.showBoxMinimap();
    }
  },

  mouseleaveHideBoxMinimap: function() {
    var that = this;

    if( that.options.openWithHover == true ) {
      that.hideBoxMinimap();
    }
  },

  showBoxMinimap: function() {
    var that = this;
    that.$el.find( '.boxMinimap' ).removeClass( 'filterMinimapNone' ).addClass( 'filterMinimapBlock' );
  },

  hideBoxMinimap: function() {
    var that = this;
    that.$el.find( '.boxMinimap' ).removeClass( 'filterMinimapBlock' ).addClass( 'filterMinimapNone' );
  },

  evReset: function(ev) {
    var that = this;
    ev.stopImmediatePropagation();
    that.reset();
  },

  reset: function() {
    var that = this;

    var newData = { 'areas': {} };
    // Reseteamos la área al color por defecto

    newData.areas[that.currentIdName] = {
      attrs: { fill: that.options.styles.background_fill }
    };
    that.$el.find( '.minimap' ).trigger( 'update', [ { mapOptions: newData } ] );

    that.$el.find( '.selectedText' ).html('');

    that.currentIdName = false;
    that.selectedTerms = false;

    that.parentExplorer.applyFilters();
    //  Ocultamos el mapa tras el reset
    that.hideBoxMinimap();
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
          /* jshint ignore:start */
          eval( 'mapaelDataObject.'+ e.get('idName') + ' = "' + e.get('geom') + '";' );
          /* jshint ignore:end */
        } catch(err) {
          cogumelo.log( 'Problema con idName :', e.get('idName') );
        }
      });
    }

    //cogumelo.log('MAPAEL en filtro', mapaelDataObject );

    return mapaelDataObject;
  }



});
