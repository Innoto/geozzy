var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

var recommenderActiveDebug = false;
var activateRecommenderDebug = false;
geozzy.explorerComponents.reccommendedListView = Backbone.View.extend({

  displayType: 'reccomendList',
  parentExplorer: false,

  events: {

  },

  initialize: function( opts ) {
    var that = this;

    activateRecommenderDebug = function(){that.activateDebug()};
  },

  activateDebug: function() {
    var that = this;

    recommenderActiveDebug = true;
    that.debuger = new TimeDebuger( {debug:true} );
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },

  render: function() {

    var that = this;

    var col = new geozzy.collection.ResourceCollection({urlAlias: true});


    if( that.parentExplorer.displays.map  && that.parentExplorer.displays.map.isReady() ) {
      var bounds = [
                    [
                      that.parentExplorer.displays.map.getMapBoundsInArray()[0][1],
                      that.parentExplorer.displays.map.getMapBoundsInArray()[0][0]
                    ],
                    [
                      that.parentExplorer.displays.map.getMapBoundsInArray()[1][1],
                      that.parentExplorer.displays.map.getMapBoundsInArray()[1][0]
                    ]
                   ];
    }
    else {
      var bounds = [[0,0],[0,0]];
    }

    var recommenderFilters = [];
    $.each(that.parentExplorer.filters, function(i,e) {
      recommenderFilters = $.merge( recommenderFilters, e.getSelectedTerms() );

    });

    geozzy.biMetricsInstances.recommender.explorer( that.parentExplorer.options.explorerId , bounds, recommenderFilters, function(res){

      // Para pruebas
      if (res.length>0){
        var res_ids = [];


        $.each(res, function(i,e){
          res_ids.push(e.resource_id)
          //cogumelo.log('recomendado por ITG:', e.resource_id)
            //that.debuger.log('Recurso recomendado:'+e.resource_id , e.resource_id);

        });

        col.fetchByIds(res_ids, function(){
          col.each( function(elm, i){
            //$(that.el).append(that.template({
            //  id:elm.get('id'),
            //  title:elm.get('title'),
            //  image:elm.get('image'),
            //  urlAlias:elm.get('urlAlias'),
            //  shortDescription:elm.get('shortDescription')
            //}));

            cogumelo.log('Recomendado ITG existe: ',elm);
            that.debuger.log('Recurso recomendado:'+elm.id , elm.id);

          });

        });

      }

    });



  }

});
