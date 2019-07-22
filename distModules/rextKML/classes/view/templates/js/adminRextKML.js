var geozzy = geozzy || {};

$(document).ready(function(){
  geozzy.adminRextLMLViewInstance = new geozzy.adminRextLMLView();
  resourceFormMap.addComponent( geozzy.adminRextLMLViewInstance );
});



geozzy.adminRextLMLViewInstance = false;
geozzy.adminRextLMLView  = Backbone.View.extend({
  parent: false,
  editing: false,
  events: {
  },


  initialize: function() {
    var that = this;


  },


  render: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationDialog' ).append('<div class="kmlFormMap" style="display:none;"></div>');
    $('.rextKML').appendTo(  '.resourceLocationFrame .locationDialog .kmlFormMap') ;


    that.parent.addToolBarbutton({
      id: 'rExtKML',
      icon: '<i class="fa  fa-layer-group" aria-hidden="true"></i>',
      onclick: function() {
        that.startEdit();
      }
    });

  },
  startEdit: function() {
    var that = this;
    that.editing = true;
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .kmlFormMap' ).show();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).addClass('disabled');
  },

  endEditCancel: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .kmlFormMap' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).removeClass('disabled');

  },

  endEditSuccess: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .kmlFormMap' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).removeClass('disabled');        

  }

});
