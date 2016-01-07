var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.pasiveListView = Backbone.View.extend({

  displayType: 'pasiveList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,
  endPage: 3,
  totalPages: false,


  events: {
      "click .explorerListPager .next" : "nextPage",
      "click .explorerListPager .previous" : "previousPage",

      // resource events
      "click .explorerListContent .accessButton": "resourceClick",
      "mouseenter .explorerListContent .element": "resourceHover",
      "mouseleave .explorerListContent": "resourceOut",
  },

  initialize: function( opts ) {


  }

});
