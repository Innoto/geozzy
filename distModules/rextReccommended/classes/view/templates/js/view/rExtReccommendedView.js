var geozzy = geozzy || {};

if(!geozzy.rextReccommended) geozzy.rextReccommended={};

geozzy.rextReccommended.reccommendedView = Backbone.View.extend({
  template: _.template($('#recommendedListTemplate').html()),
  el: '.rExtReccommendedList',
  options: false,
  events: {

  },
  initialize: function( opts ) {
    var that = this;

    var options = {
      onRender: function(){}
    };

    that.options = $.extend(true, {}, that.options, opts);

    if( typeof geozzy.biMetricsInstances != 'undefined' ) {
      that.render();
    }
    else {
      cogumelo.log('rExtReccommender: geozzy.biMetricInstances not defined');
    }

  },
  render: function() {

    var that = this;
    var collection = new geozzy.collection.ResourceCollection({urlAlias: true});
    var col = new geozzy.collection.ResourceCollection({urlAlias: true});
    var recommendedResources = geozzy.biMetricsInstances.recommender.resource( geozzy.rExtReccommendedOptions.resId, function(res){

      // Para pruebas
      //res = [{"resource_id":24,"recommendation":"3000"},{"resource_id":23,"recommendation":"3000"},{"resource_id":18,"recommendation":"3000"}];
      if (res.length>0){
        var res_ids = [];

        $.each(res, function(i,e){
          res_ids.push(e.resource_id);
        });
        collection.fetchByIds(res_ids, function(){
          $(res_ids).each(function(i, e){
            var elm = false;
            if (elm = collection.get(e)){
              $(that.el).prepend(that.template({
                id:elm.get('id'),
                title:elm.get('title'),
                image:elm.get('image'),
                urlAlias:elm.get('urlAlias'),
                shortDescription:elm.get('shortDescription')
              }));
            }
          });

          that.options.onRender();
        });

      }

    });

  }

});
