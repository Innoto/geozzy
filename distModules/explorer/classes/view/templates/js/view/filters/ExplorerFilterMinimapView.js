var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterMinimapView = geozzy.filterView.extend({

  isTaxonomyFilter: true,

  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      mainContainerClass: false,
      containerClass: false,
      template: geozzy.explorerComponents.filterMinimapViewTemplate,
      data: false,
      onChange: function(selectedId) {}
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



    // TRIGER CAMBIO DE ESTADO
    //that.selectedTerms = [ parseInt( valor ) ];





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
