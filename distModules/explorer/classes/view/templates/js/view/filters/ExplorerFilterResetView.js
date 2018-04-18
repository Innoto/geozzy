var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterResetView = geozzy.filterView.extend({

    isTaxonomyFilter: true,
    template: false,

    initialize: function( opts ) {
      var that = this;

      var options = {
        title: 'Reset filters',
        template:  geozzy.explorerComponents.filterResetTemplate,
        onChange: function(){}
      };

      that.options = $.extend(true, {}, options, opts);

      that.template = _.template( that.options.template );
    },

    filterAction: function( model ) {
      return true;
    },

    render: function() {
      var that = this;

      var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
      var filterHtml = that.template( { filterClass: that.options.containerClass, title: that.options.title } );

      // Print filter html into div
      if( !$(  that.options.mainContainerClass+' .' +that.options.containerClass ).length ) {
        $( that.options.mainContainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
      }
      else {
        $( that.options.mainContainerClass+' ' + containerClassDots ).html( filterHtml );
      }

      $( that.options.mainContainerClass + ' ' + containerClassDots + ' button').bind('click', function(el) {
        that.actionResetAllFilters();
      });


    },


    actionResetAllFilters: function() {
      var that = this;
      $.each( that.parentExplorer.filters, function(i,e) {
        e.reset();
      });
      that.parentExplorer.applyFilters();
      that.options.onChange();
    }

});
