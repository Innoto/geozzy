var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSearchView = geozzy.filterView.extend({
  selectedTerms: false,
  categoriaEtiqueta: false,
  searchStr: '',
  initialize: function( opts ) {
    var that = this;
    var options = {
      mainContainerClass: false,
      onChange: function(){}
    };
    that.options = $.extend(true, {}, options, opts);

    that.categoriaEtiqueta = new geozzy.collection.CategorytermCollection();
    that.categoriaEtiqueta.setUrlByIdName('appEtiquetas');
    that.categoriaEtiqueta.fetch();

  },

  filterAction: function( model ) {
    var that = this;
    var ret = false;
/*
    var strA = '';
    var strB = '';

    if( that.searchStr != '' ){

      strA = that.searchStr.toUpperCase();
    }

    if( model.get('title') ) {
      strB = model.get('title').toUpperCase();
    }

    if( strB.search( strA ) != -1) {
      ret = true;
    }

    if( that.selectedTerms != false ) {

      var terms =  model.get('terms');

      if( typeof terms != "undefined") {
        var diff = $( terms ).not( that.selectedTerms );
        ret = (diff.length != terms.length );
      }
    }
*/

    if(that.searchStr!='') {
      $.post(
        '/api/explorerSearch',
        {searchString: that.searchStr},
        function( data ) {
          console.log('Resultado BUSQUEDA!',resultTerms);

          var terms =  model.get('terms');

          if( typeof terms != "undefined") {
            var diff = $( terms ).not( that.resultTerms );
            ret = (diff.length != terms.length );
          }

        }
      );
    }

    return ret;
  },

  render: function() {
    var that = this;


    $(that.options.mainContainerClass).html('<input type="text" placeholder="Introduce a tua búsqueda"><button class="search"><i class="ti-search"></i></button>');

     $(that.options.mainContainerClass + ' input').on('keyup', function(e){
      if(e.keyCode == 13) {
        that.searchFind();
      }
    });

    $(that.options.mainContainerClass).find('.search').on('click', function() {
      that.searchFind();
    });

  },

  searchFind: function() {
    var that = this;

    that.searchStr = $(that.options.mainContainerClass + ' input').val() ;

    that.options.onChange();

    that.selectedTerms = [];

    that.categoriaEtiqueta.each( function(e,i){
      if( e.get('name').search(that.searchStr) != -1 ) {
        that.selectedTerms.push( e.get('id') );
      }

    });

    that.parentExplorer.applyFilters();
  },

  reset: function() {
    var that = this;
    that.searchStr = '';
    $(that.options.mainContainerClass + ' input').val('');
  }

});
