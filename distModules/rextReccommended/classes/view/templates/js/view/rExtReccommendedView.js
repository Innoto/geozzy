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

    var recommendedResources = geozzy.biMetricsInstances.recommender.resource( '19', function(res){

      var res = '[{"resource_id":27,"recommendation":"2900"},{"resource_id":28,"recommendation":"2900"},{"resource_id":29,"recommendation":"2900"}]';
      var res_ids = [];
      $(JSON.parse(res)).each(function(index,e){
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
    });

  }

});
