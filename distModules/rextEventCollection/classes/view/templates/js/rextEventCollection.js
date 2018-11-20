$(document).ready(function(){
  rextEventCollectionJs.initResource();
});

var rextEventCollectionJs = {
  initResource: function() {
    var that = this;
    $('.accordion-heading').bind( 'click', function() {
      that.changeArrow(this);
    } );
  },

  changeArrow: function( elem ) {
    var that = this;
    $( elem ).find('i').toggle();
  }
};
