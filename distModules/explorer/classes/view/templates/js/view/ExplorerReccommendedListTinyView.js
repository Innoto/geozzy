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

  render: function() {

    var that = this;
    var col = new geozzy.collection.ResourceCollection({urlAlias: true});


    if( typeof that.parentExplorer.displays.map  != 'undefined') {
      var bounds = that.parentExplorer.displays.map.getMapBounds();
    }
    else {
      var bounds = [];
    }


    var recommendedResources = geozzy.biMetricsInstances.recommender.explorer( that.parentExplorer.explorerID , bounds, function(res){

      // Para pruebas
      if (res.length>0){
        var res_ids = [];

        $.each(res, function(i,e){
          res_ids.push(e.resource_id)
        });

        col.fetchByIds(res_ids, function(){
          col.each( function(elm, i){
            /*$(that.el).append(that.template({
              id:elm.get('id'),
              title:elm.get('title'),
              image:elm.get('image'),
              urlAlias:elm.get('urlAlias'),
              shortDescription:elm.get('shortDescription')
            }));*/

            console.log(elm);
          });

        });

      }

    });


  }

});
