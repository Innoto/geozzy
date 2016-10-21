/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.rTypeCommunityController = geozzy.rTypeCommunityController || {

  ajaxError: function ajaxError( $jqXHR, textStatus, errorThrown ) {
    console.log( 'geozzy rTypeCommunityController ajaxError', $jqXHR, textStatus, errorThrown );
    if( $jqXHR.status === 403 ) {
      location.reload();
    }
  },

  // MYCOMMUNITY

  editCommunity: function editCommunity() {
    geozzy.rTypeCommunityController.editMode();
  }, // editCommunity()

  saveCommunity: function saveCommunity() {
    geozzy.rTypeCommunityController.unbindMyCommunity();

    var formData = new FormData();
    formData.append( 'cmd', 'setMyCommunity' );
    formData.append( 'status', $('.commRS input:checked').val() );
    formData.append( 'user', geozzy.rTypeCommunityData.userSessionId );
    formData.append( 'facebook', $('.commRS input[name=facebookAccount]').val() );
    formData.append( 'twitter', $('.commRS input[name=twitterAccount]').val() );

    $.ajax({
      url: '/api/community', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( jsonData, textStatus, $jqXHR ) {
        if ( jsonData.result === 'ok' ) {
          geozzy.rTypeCommunityController.updateCommunityInfo( jsonData.status );
        }
        else {
          console.log( 'geozzy rTypeCommunityController success error', jsonData, textStatus, $jqXHR );
          location.reload();
        }
      },
      error: geozzy.rTypeCommunityController.ajaxError
    });
  }, // sendCommunityInfo()

  updateCommunityInfo: function updateCommunityInfo( commInfo ) {
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


  // FOLLOW

  toggleFollow: function toggleFollow() {
    // $(this).find('.showStatus').toggle();
    $elem = $(this);
    $newStatus = ( $elem.attr('data-follow') == 1 ) ? 0 : 1;
    geozzy.rTypeCommunityController.saveFollow( $newStatus, $elem.attr('data-id') );
  }, // toggleFollow()

  saveFollow: function saveFollow( status, followUser ) {
    geozzy.rTypeCommunityController.unbindFollow();

    var formData = new FormData();
    formData.append( 'cmd', 'setFollow' );
    formData.append( 'status', status );
    formData.append( 'user', geozzy.rTypeCommunityData.userSessionId );
    formData.append( 'followUser', followUser );

    $.ajax({
      url: '/api/community', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( jsonData, textStatus, $jqXHR ) {
        if ( jsonData.result === 'ok' ) {
          geozzy.rTypeCommunityController.updateFollowInfo( jsonData.status );
        }
        else {
          console.log( 'geozzy rTypeCommunityController success error', jsonData, textStatus, $jqXHR );
          location.reload();
        }
      },
      error: geozzy.rTypeCommunityController.ajaxError
    });
  }, // saveFollow()

  updateFollowInfo: function updateFollowInfo( followInfo ) {
    $elem = $('.rtypeCommunity .actionFollow[data-id='+followInfo.user+']');

    $elem.attr( 'data-follow', followInfo.status );

    if( followInfo.status == 1 ) {
      $elem.find('.showStatus.off').hide();
      $elem.find('.showStatus.on').show();
    }
    else {
      $elem.find('.showStatus.on').hide();
      $elem.find('.showStatus.off').show();
    }

    geozzy.rTypeCommunityController.bindFollow();
  }, // updateCommunityInfo()

  bindFollow: function bindFollow() {
    $('.rtypeCommunity .actionFollow')
      .off( 'click.rTypeCommunityController' )
      .on( 'click.rTypeCommunityController', geozzy.rTypeCommunityController.toggleFollow );
  }, // bindFollow()

  unbindFollow: function unbindFollow() {
    $('.rtypeCommunity .actionFollow').off( 'click.rTypeCommunityController' );
  }, // unbindFollow()
}; // geozzy.rTypeCommunityController



$( document ).ready(function() {
  geozzy.rTypeCommunityController.bindMyCommunity();
  geozzy.rTypeCommunityController.bindFollow();
});

