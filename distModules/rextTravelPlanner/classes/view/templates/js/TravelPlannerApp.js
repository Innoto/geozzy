var geozzy = geozzy || {};


geozzy.travelPlanner = function( idTravelPlanner ) {

  var that = this;
  that.timeServerFormat = 'YYYY/MM/DD HH:mm:ss';
  that.travelPlannerId = (idTravelPlanner) ? idTravelPlanner : false;
  that.travelPlannerInterfaceView = false;
  that.travelPlannerDatesView = false;
  that.travelPlannerPlanView = false;
  that.travelPlannerResourceView = false;
  that.travelPlannerDefaultVisitTime = 116; // in minutes

  if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
    moment.locale(cogumelo.publicConf.C_LANG);
  }

  var resParam = {
    fields: false,
    filters: false,
    rtype: false,
    urlAlias: true
  }
  if(cogumelo.publicConf.mod_geozzy_travelPlanner.toString() !== ''){
    resParam.rtype = cogumelo.publicConf.mod_geozzy_travelPlanner.toString();
  }

  that.resources = new geozzy.collection.ResourceCollection( resParam );
  that.rtypes = new geozzy.collection.ResourcetypeCollection( );
  that.favInfo = false;
  that.favResources = false;
  that.dateFormat = 'DD/MM/YYYY';

  that.tpData = new geozzy.travelPlannerComponents.TravelPlannerModel();
  that.tpData.set('id',idTravelPlanner);

  that.getResourcesFav = function(){
    var formData = new FormData();
    formData.append( 'cmd', 'listFavs' );

    var url = '/api/favourites';

    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    return $.ajax({
      url: url, type: 'POST',
      data: formData, cache: false,
      contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          that.favInfo = $jsonData.favourites;

          if ( $jsonData.favourites.length > 0 ){
           that.favResources = $jsonData.favourites[Object.keys($jsonData.favourites)[0]].resourceList;
         }
        }
      }
    });
  },
  // First Execution
  that.init = function( ) {

    geozzy.travelPlannerComponents.routerInstance = new geozzy.travelPlannerComponents.mainRouter();
    geozzy.travelPlannerComponents.routerInstance.parentTp = that;
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
    else {
      Backbone.history.stop();
      Backbone.history.start();
    }



    $.when(
      that.resources.fetch(),
      that.rtypes.fetch(),
      that.getResourcesFav(),
      that.tpData.fetchData()
    ).done( function() {

      that.travelPlannerInterfaceView = new geozzy.travelPlannerComponents.TravelPlannerInterfaceView(that);

      that.initDates();

      if( that.tpData.get('checkin') !== null || that.tpData.get('checkout') !== null ){
        that.initPlan();
      }
    });
  },
  that.initDates = function(){
    that.travelPlannerDatesView = new geozzy.travelPlannerComponents.TravelPlannerDatesView(that);
  },
  that.initPlan = function(){
    if( that.tpData.get('checkin') !== null || that.tpData.get('checkout') !== null ){
      that.travelPlannerPlanView = new geozzy.travelPlannerComponents.TravelPlannerPlanView(that);
    }
  },
  that.addToPlan = function(idRes){
    if( that.tpData.get('checkin') !== null || that.tpData.get('checkout') !== null ){
      that.travelPlannerResourceView = new geozzy.travelPlannerComponents.TravelPlannerResourceView( that, idRes );
    }else{
      alert("Select dates first");
    }
  },
  that.editResourceToPlan = function(data){
    that.travelPlannerResourceView = new geozzy.travelPlannerComponents.TravelPlannerResourceView( that, data.id, data, 'edit' );
  },
  that.momentDate = function( date ) {
    return moment( date, that.timeServerFormat );
  },
  that.openResource = function( resourceId ) {
    $(".tpDuResource").show();
    $(".tpDuResource").load(
      '/'+cogumelo.publicConf.C_LANG+'/resource/'+resourceId,
      { pf: 'blk' },
      function() {
        //$('html, body').css('overflowY', 'hidden');
        //$(".storyContainer.story-loading").hide();
        //$(".storyContainer.story-container-du").show();
      }
    );
  },

  that.closeResource = function() {
    $('.tpDuResource').hide();
    $('.tpDuResource').html('')
  }
}
