var geozzy = geozzy || {};

$( document ).ready(function() {

  createFavouritesSelector();
  addTravelPlannerLink();

});



function createFavouritesSelector() {
  var url = '/api/core/resourcetypes';
  if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
    url = '/'+cogumelo.publicConf.C_LANG+url;
  }

  $.ajax({
    url: url, type: 'GET',
    contentType: false, processData: false,
    success: function getRTypesInfo( $jsonData, $textStatus, $jqXHR ) {
      if ( $textStatus === 'success' ) {
        showFavouritesSelector( $jsonData );
      }
    }
  });
}

function showFavouritesSelector( rTypeIdsInfo ) {
  var options = {
    '0':'All'
  };

  var rTypeNames = {};
  $.each( rTypeIdsInfo, function ( index, rTypeIdObj ) {
    rTypeNames[ rTypeIdObj.id ] = rTypeIdObj.name;
  });

  $favs = $('.favouritesElement').each( function() {
    var rtypeid = $( this ).attr('data-rtypeid');
    options[ rtypeid ] = rTypeNames[ rtypeid ];
  });

  $favsSelector = $( '<select>' )
    .addClass( 'favouritesSelector' )
    .on( 'change', filterFavouritesView );

  $.each( options, function (id, text) {
    $favsSelector.append($('<option>', {
      value: id,
      text : text
    }));
  });

  $('.favsHeader').append( $favsSelector );
}

function filterFavouritesView() {
  var rtypeid = $(this).val();
  $('.favouritesElement').show();
  if( rtypeid !== '0' ) {
    $('.favouritesElement[data-rtypeid!='+rtypeid+']').hide();
  }
}

function addTravelPlannerLink() {
  $travelLink = $( '<a>' )
    .addClass( 'rExtTravelPlannerLink' )
    .text( __('Travel Planner') );

  $('.favsHeader, .favsFooter').append( $travelLink );

  if( geozzy.travelPlannerLoader ) {
    geozzy.travelPlannerLoader.setBinds();
  }
}
