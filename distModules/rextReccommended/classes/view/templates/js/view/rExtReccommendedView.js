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

    that.render();
  },
  render: function() {

    var that = this;
    var col = new geozzy.collection.ResourceCollection({urlAlias: true});

    var recommendedResources = geozzy.biMetricsInstances.recommender.resource( geozzy.rExtReccommendedOptions.resId, function(res){

      // Para pruebas
      // var res = [{"resource_id":27,"recommendation":"3000"},{"resource_id":28,"recommendation":"3000"},{"resource_id":29,"recommendation":"3000"}];
      if (res.length>0){
        var res_ids = [];

        $.each(res, function(i,e){
          res_ids.push(e.resource_id)
        });

        col.fetchByIds(res_ids, function(){
          col.each( function(elm, i){
            $(that.el).append(that.template({
              id:elm.get('id'),
              title:elm.get('title'),
              image:elm.get('image'),
              urlAlias:elm.get('urlAlias'),
              shortDescription:elm.get('shortDescription')
            }));
          });
          that.options.onRender();
        });

      }

    });

  }

});
