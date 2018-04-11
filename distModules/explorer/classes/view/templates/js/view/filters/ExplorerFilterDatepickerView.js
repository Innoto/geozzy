var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterBDatepickerView = geozzy.filterView.extend({

  initialize: function( opts ) {
    var that = this;

    var options = {
      mainContainerClass: false,
      containerClass: false,
      isRange:false,
      onChange: function(){}
    };
    that.options = $.extend(true, {}, options, opts);
  },

  filterAction: function( model ) {
    var that = this;
    var ret = false;

var coincide=true;
    if( coincide ) {
      ret = true;
    }

    return ret;
  },

  render: function() {
    var that = this;

    // Print filter html into div
    if( !$(  that.options.mainContainerClass+' .' +that.options.containerClass ).length ) {
      $( that.options.mainContainerClass).append( '<input class="explorerFilterElement '+ that.options.containerClass +'">' );
    }
    $(  that.options.mainContainerClass+' .' +that.options.containerClass + '' ).daterangepicker({
      singleDatePicker: !that.options.isRange
    });

    //$(  that.options.mainContainerClass+' .' +that.options.containerClass + '').trigger( "click" );

  },

  reset: function() {
    var that = this;
    /*var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
    $( that.options.mainContainerClass + ' ' + containerClassDots + ' ul li').removeClass('selected');
    that.selectedTerms = false;*/
  }

});
