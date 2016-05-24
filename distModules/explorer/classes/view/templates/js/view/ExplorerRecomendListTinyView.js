var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.pasiveListView = Backbone.View.extend({

  displayType: 'pasiveList',
  parentExplorer: false,
  visibleResources: [],



  events: {
      "click .explorerListPager .next" : "nextPage",
      "click .explorerListPager .previous" : "previousPage",

      // resource events
      "click .explorerListContent .accessButton": "resourceClick",
      "mouseenter .explorerListContent .element": "resourceHover",
      "mouseleave .explorerListContent": "resourceOut",
  },

  initialize: function( opts ) {


  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  }

});
