var geozzy = geozzy || {};

/*
if( typeof geozzy.rextBIBindedIds == 'undefined' ) {
  geozzy.rextBIBindedIds = [];
}*/


geozzy.rExtBIController = function( opts ) {
  var that = this;


  that.initialize = function() {
    //alert( geozzy.rExtBIOptions.resourceID );
    if( typeof geozzy.biMetricsInstances != 'undefined') {
      geozzy.biMetricsInstances.resource.eventAccessedStart( geozzy.rExtBIOptions.resourceID, 'Resource page' );
      that.bindRelatedResources();      
    }

  };

  that.bindRelatedResources = function() {


    $('.item.isResource').each( function(i,resource) {
      var resourceId = parseInt( $(resource).attr('data-related-resource-id') );
      geozzy.biMetricsInstances.resource.eventPrint( resourceId, 'Resource page, related resources' );

      $(resource).bind('mouseenter', function(){
        geozzy.biMetricsInstances.resource.eventHoverStart( resourceId, 'Resource page, related resources' );
      });

      $(resource).bind('mouseleave', function(){
        geozzy.biMetricsInstances.resource.eventHoverEnd( resourceId );
      });

      $(resource).bind('click', function(){
        geozzy.biMetricsInstances.resource.eventClick( resourceId, 'Resource page, related resources' );
      });


      // Set the binds !!
  /*    if( jQuery.inArray( parseInt(resourceId), geozzy.rextBIBindedIds ) == -1 ) {

  }*/

      //in array( parseInt(resourceId) geozzy.rextBIBindedIds  )

    });
  };

};
