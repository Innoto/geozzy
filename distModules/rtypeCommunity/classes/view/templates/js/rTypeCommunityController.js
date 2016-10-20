/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.rTypeCommunityController = geozzy.rTypeCommunityController || {

  editCommunity: function editCommunity() {
    console.log( 'editCommunity' );
    geozzy.rTypeCommunityController.editMode();
  }, // editCommunity()

  saveCommunity: function saveCommunity() {
    console.log( 'saveCommunity' );

    geozzy.rTypeCommunityController.unbindMyCommunity();

    var formData = new FormData();
    formData.append( 'cmd', 'setMyCommunity' );
    formData.append( 'status', $('.commRS input:checked').val() );
    formData.append( 'facebook', $('.commRS input[name=facebookAccount]').val() );
    formData.append( 'twitter', $('.commRS input[name=twitterAccount]').val() );

    console.log(formData);

    $.ajax({
      url: '/api/community', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          geozzy.rTypeCommunityController.updateCommunityInfo( $jsonData.status );
        }
      }
    });
  }, // sendCommunityInfo()

  updateCommunityInfo: function updateCommunityInfo( commInfo ) {
    console.log( 'updateCommunityInfo', commInfo );

    geozzy.rTypeCommunityData.myCommunity = commInfo;

    if( commInfo.status ) {
      $('.commRS .shareOff' ).hide();
      $('.commRS .shareOn' ).show();
    }
    else {
      $('.commRS .shareOff' ).show();
      $('.commRS .shareOn' ).hide();
    }
    $('.commRS .facebookAccount').text( (commInfo.facebook) ? commInfo.facebook : 'No indicado' );
    $('.commRS input[name=facebookAccount]').val(commInfo.facebook);

    $('.commRS .twitterAccount').text( (commInfo.twitter) ? commInfo.twitter : 'No indicado' );
    $('.commRS input[name=twitterAccount]').val(commInfo.twitter);

    geozzy.rTypeCommunityController.bindMyCommunity();
    geozzy.rTypeCommunityController.viewMode();
  }, // updateCommunityInfo()

  viewMode: function viewMode() {
    $('.commRS .edit').hide();
    $('.commRS .view').show();
  }, // viewMode()

  editMode: function editMode() {
    $('.commRS .edit').show();
    $('.commRS .view').hide();
  }, // editMode()

  bindMyCommunity: function bindMyCommunity() {
    $('.commRS .actionEdit')
      .off( 'click.rTypeCommunityController' )
      .on( 'click.rTypeCommunityController', geozzy.rTypeCommunityController.editCommunity );
    $('.commRS .actionSave')
      .off( 'click.rTypeCommunityController' )
      .on( 'click.rTypeCommunityController', geozzy.rTypeCommunityController.saveCommunity );
  }, // bindMyCommunity()

  unbindMyCommunity: function unbindMyCommunity() {
    $('.commRS .actionEdit').off( 'click.rTypeCommunityController' );
    $('.commRS .actionSave').off( 'click.rTypeCommunityController' );
  }, // unbindMyCommunity()
} // geozzy.rTypeCommunityController



$( document ).ready(function() {
  console.log( 'READY JS Community' );
  geozzy.rTypeCommunityController.bindMyCommunity();
});

