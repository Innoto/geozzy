/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.rTypeCommunityController = geozzy.rTypeCommunityController || {

  ajaxError: function ajaxError( $jqXHR, textStatus, errorThrown ) {
    cogumelo.log( 'geozzy rTypeCommunityController ajaxError', $jqXHR, textStatus, errorThrown );
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
          cogumelo.log( 'geozzy rTypeCommunityController success error', jsonData, textStatus, $jqXHR );
          location.reload();
        }
      },
      error: geozzy.rTypeCommunityController.ajaxError
    });
  }, // sendCommunityInfo()

  updateCommunityInfo: function updateCommunityInfo( commInfo ) {
    geozzy.rTypeCommunityData.myCommunity = commInfo;
    $('.commRS input[name=facebookAccount]').val(commInfo.facebook);
    $('.commRS input[name=twitterAccount]').val(commInfo.twitter);

    geozzy.rTypeCommunityController.showCommunityInfo();
  }, // updateCommunityInfo()

  showCommunityInfo: function showCommunityInfo() {
    commInfo = geozzy.rTypeCommunityData.myCommunity;

    // Compartir datos con comunidad
    if( commInfo.share === 1 ) {
      $('.myShare .shareOff' ).hide();
      $('.myShare .shareOn' ).show();
    }
    else {
      $('.myShare .shareOff' ).show();
      $('.myShare .shareOn' ).hide();
    }

    // Enlazar con Facebook
    if( commInfo.facebook ) {
      $('.rs .myFacebook .shareOff' ).hide();
      $('.rs .myFacebook .shareOn' ).show();
      $('.rs .myFacebook').removeClass('noShare');
    }
    else{
      $('.rs .myFacebook .shareOff' ).show();
      $('.rs .myFacebook .shareOn' ).hide();
      $('.rs .myFacebook').addClass('noShare');
    }

    // Enlazar con Twitter
    if( commInfo.twitter ) {
      $('.rs .myTwitter .shareOff' ).hide();
      $('.rs .myTwitter .shareOn' ).show();
      $('.rs .myTwitter').removeClass('noShare');
    }
    else{
      $('.rs .myTwitter .shareOff' ).show();
      $('.rs .myTwitter .shareOn' ).hide();
      $('.rs .myTwitter').addClass('noShare');
    }

    // $('.commRS .facebookAccount').text( (commInfo.facebook) ? commInfo.facebook : 'No indicado' );
    // $('.commRS .twitterAccount').text( (commInfo.twitter) ? commInfo.twitter : 'No indicado' );

    geozzy.rTypeCommunityController.bindMyCommunity();
    geozzy.rTypeCommunityController.viewMode();
  }, // showCommunityInfo()

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
          cogumelo.log( 'geozzy rTypeCommunityController success error', jsonData, textStatus, $jqXHR );
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


  // Show other user Community

  showOtherCommunity: function showOtherCommunity( evnt ) {
    geozzy.rTypeCommunityController.unbindShowOtherCommunity();

    var formData = new FormData();
    formData.append( 'cmd', 'getOtherCommunityView' );
    formData.append( 'user', geozzy.rTypeCommunityData.userSessionId );
    formData.append( 'followUser', $( this ).attr('data-id') );

    $.ajax({
      url: '/api/community', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( jsonData, textStatus, $jqXHR ) {
        if ( jsonData.result === 'ok' ) {
          geozzy.rTypeCommunityController.updateModal( jsonData.view );
        }
        else {
          cogumelo.log( 'geozzy rTypeCommunityController success error', jsonData, textStatus, $jqXHR );
          location.reload();
        }
      },
      error: geozzy.rTypeCommunityController.ajaxError
    });
  },

  updateModal: function updateModal( htmlContent ) {
    $('#communityFavsModal .modal-body').html( htmlContent );
    this.openModal();
    this.bindShowOtherCommunity();
  },

  openModal: function openModal() {
    // geozzy.rTypeCommunityController.openModal();
    $('#communityFavsModal').modal({
      'show' : true,
      'keyboard': false,
      'backdrop' : 'static'
    });
  },

  bindShowOtherCommunity: function bindShowOtherCommunity() {
    $('.rtypeCommunity .actionShowAll')
      .off( 'click.rTypeCommunityController' )
      .on( 'click.rTypeCommunityController', geozzy.rTypeCommunityController.showOtherCommunity );
  },

  unbindShowOtherCommunity: function bindShowOtherCommunity() {
    $('.rtypeCommunity .actionShowAll')
      .off( 'click.rTypeCommunityController' );
  },

}; // geozzy.rTypeCommunityController



$( document ).ready(function() {
  geozzy.rTypeCommunityController.showCommunityInfo();
  geozzy.rTypeCommunityController.bindFollow();
  geozzy.rTypeCommunityController.bindShowOtherCommunity();
});
