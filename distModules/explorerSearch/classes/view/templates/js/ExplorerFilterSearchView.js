var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSearchView = geozzy.filterView.extend({
  searchStr: '',
  serverResponse: false,
  initialize: function( opts ) {
    var that = this;
    var options = {
      template: '<input type="text" placeholder="'+__('Search by name')+'"><button class="search fa fa-search"></button> <button style="display:none;" class="clear fa fa-times-circle"></button> ',
      mainContainerClass: false,
      onChange: function(){}
    };
    that.options = $.extend(true, {}, options, opts);

  },

  filterAction: function( model ) {
    var that = this;
    var ret = true;

    if( that.searchStr != '' && that.serverResponse.length > 0 ) {
      console.log(model.get('id'),that.serverResponse)
      if( $.inArray( model.get('id'), that.serverResponse ) != -1) {
        ret = true;
      }
      else {
        ret = false;
      }
    }

    return ret;
  },

  render: function() {
    var that = this;


    $(that.options.mainContainerClass).html(that.options.template);

     $(that.options.mainContainerClass + ' input').on('keyup', function(e){
      if(e.keyCode == 13) {
        that.searchFind();
      }
    });

    $(that.options.mainContainerClass).find('.search').on('click', function() {
      that.searchFind();
    });

    $(that.options.mainContainerClass).find('.clear').on('click', function() {
      that.reset();
    });
  },

  searchFind: function() {
    var that = this;

    $(that.options.mainContainerClass).find('button.search').hide();
    $(that.options.mainContainerClass).find('button.clear').show();

    that.searchStr = $(that.options.mainContainerClass + ' input').val() ;

    $.post(
      '/api/explorerSearch',
      {searchString: that.searchStr},
      function( data ) {
        that.serverResponse = data;
        that.options.onChange();
        that.parentExplorer.applyFilters();
      }
    );
  },

  reset: function() {
    var that = this;
    that.searchStr = '';

    $(that.options.mainContainerClass + ' input').val('');
    that.searchFind();
    $(that.options.mainContainerClass).find('button.search').show();
    $(that.options.mainContainerClass).find('button.clear').hide();

  }

});
