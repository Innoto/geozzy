var geozzy = geozzy || {};


geozzy.travelPlanner = function( idTravelPlanner ) {

  var that = this;

  that.travelPlannerId = (idTravelPlanner) ? idTravelPlanner : false;
  that.travelPlannerInterfaceView = false;
  that.travelPlannerDatesView = false;
  that.travelPlannerPlanView = false;

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

  that.getResourcesFav = function(){
    var formData = new FormData();
    formData.append( 'cmd', 'listFavs' );

    var url = '/api/favourites';

    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    $.ajax({
      url: url, type: 'POST',
      data: formData, cache: false,
      contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          that.favInfo = $jsonData.favourites;
          that.favResources = $jsonData.favourites[Object.keys($jsonData.favourites)[0]].resourceList;
        }
      }
    });
  }
  // First Execution
  that.init = function( ) {
    console.log('travelPlannerID:'+ that.travelPlannerId );
    that.getResourcesFav();

    $.when( that.resources.fetch(), that.rtypes.fetch() ).done(function() {
      that.travelPlannerInterfaceView = new geozzy.travelPlannerComponents.TravelPlannerInterfaceView(that);
      that.initDates();
      if( that.tpData.get('checkin') !== false || that.tpData.get('checkout') !== false ){
        console.log('initPlan INIT');
        that.initPlan();
      }
    });
  }
  that.initDates = function(){
    that.travelPlannerDatesView = new geozzy.travelPlannerComponents.TravelPlannerDatesView(that);
  }
  that.initPlan = function(){
    if( that.tpData.get('checkin') !== false || that.tpData.get('checkout') !== false ){
      that.travelPlannerPlanView = new geozzy.travelPlannerComponents.TravelPlannerPlanView(that);
    }
  }
}
