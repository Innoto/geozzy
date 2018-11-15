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
        elSummaryContainer:false,
        template:  geozzy.explorerComponents.filterResetTemplate,
        onChange: function(){}
      };

      that.options = $.extend(true, {}, options, opts);
      that.template = _.template( that.options.template );
      that.$elSummaryContainer = $(that.options.elSummaryContainer);
    },

    filterAction: function( model ) {
      return true;
    },

    render: function() {
      var that = this;

      var filterHtml = that.template( { title: that.options.title } );
      that.$el.html( filterHtml );
      that.$el.find('button').bind('click', function(el) {
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
