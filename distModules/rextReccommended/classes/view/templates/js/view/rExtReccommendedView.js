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


    var col = new geozzy.collection.ResourceCollection();


    var recommendedResources = geozzy.biMetricsInstances.recommender.resource( '34', function(res){
      res= [43, 40, 46];
      col.fetchByIds(res, function(){
        col.each( function(elm, i){
          $(that.el).append(that.template({
            id:elm.get('id'),
            title:elm.get('title'),
            image:elm.get('image'),
            //urlAlias:elm.get('urlAlias'),
            shortDescription:elm.get('shortDescription')
          }));
        });
        that.options.onRender();
      });
    });

  }

});
