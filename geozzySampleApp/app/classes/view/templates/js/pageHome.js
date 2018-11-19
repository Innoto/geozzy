$( document ).ready( function() {
  home.initEffectNavs();
  home.headerTransparent();
} );

var home = {
  headerTransparent: function() {
    var that = this;

    $( '.navbar' ).addClass( 'transparent' );
    $( window ).bind( 'scroll', function() {
      if( $( this ).scrollTop() > 0 ) {
        $( '.navbar' ).removeClass( 'transparent' );
      }
      else{
        $( '.navbar' ).addClass( 'transparent' );
      }
    } );
  },

  initEffectNavs: function() {
    var that = this;

    $( 'a.page-scroll' ).bind( 'click', function scrollSuave( event ) {
      var hrefText = $( this ).attr( 'href' );
      if( hrefText.indexOf( '#' ) === 0 ) {
        var $anchor = $( hrefText );
        if( $anchor.length > 0 ) {
          $( 'html, body' ).stop().animate( {
              scrollTop: $anchor.offset().top
          }, 1500, 'easeInOutExpo' );
          event.preventDefault();
        }
      }
    } );
  }
};
