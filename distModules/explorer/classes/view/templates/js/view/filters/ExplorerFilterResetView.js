var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterResetView = geozzy.filterView.extend({

    isTaxonomyFilter: true,
    template: _.template(
      "<div class='<%= filterClass %>'>"+
        "<button><%= title %></button> "+
      "</div>"
    ),

    initialize: function( opts ) {
      var that = this;
      that.options = $.extend(true, {}, that.options, opts);
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
    }

});
