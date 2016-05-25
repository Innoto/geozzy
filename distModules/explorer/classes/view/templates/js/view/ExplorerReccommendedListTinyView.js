var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.reccommendedListView = Backbone.View.extend({

  displayType: 'activeList',
  parentExplorer: false,

  events: {
      /*"click .explorerListPager .next" : "nextPage",
      "click .explorerListPager .previous" : "previousPage",

      // resource events
      "click .explorerListContent .accessButton": "resourceClick",
      "mouseenter .explorerListContent .element": "resourceHover",
      "mouseleave .explorerListContent": "resourceOut",*/
  },

  initialize: function( opts ) {


  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },




});
