var geozzy = geozzy || {};

$(document).ready(function(){
  geozzy.adminRextPoiCollectionViewInstance = new geozzy.adminRextPoiCollectionView();
  resourceFormMap.addComponent( geozzy.adminRextPoiCollectionViewInstance );
});



geozzy.adminRextPoiCollectionViewInstance = false;
geozzy.adminRextPoiCollectionView  = Backbone.View.extend({
  parent: false,
  editing: false,
  events: {
  },


  initialize: function() {
    var that = this;
  },

  render: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationDialog' ).append('<div class="poiCollectionFormMap" style="display:none;"></div>');
    $('.rextPoiCollection').appendTo(  '.resourceLocationFrame .locationDialog .poiCollectionFormMap') ;


    $('select.cgmMForm-field-rExtPoiCollection_pois').multiList({
      itemActions : [
        { 'id': 'edit', 'html': '<i class="far fa-edit"></i>', 'action': that.editModalForm }
      ],
      placeholder: __('Select options')
    });
    $('#addPois').on('click', function(){
      app.mainView.loadAjaxContentModal('/rtypePoi/poi/create', 'createPoiModal', { title: __('Create POI') } );
    });

    that.parent.addToolBarbutton({
      id: 'rExtPoiCollection',
      icon: '<i class="fa  fa-map-pin" aria-hidden="true"></i>',
      onclick: function() {
        that.startEdit();
      }
    });

  },

  editModalForm: function(e){
    app.mainView.loadAjaxContentModal('/rtypePoi/poi/edit/'+e.value, 'editModalForm', { title: __('Edit POI Collection') } );
  },

  startEdit: function() {
    var that = this;
    that.editing = true;
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .poiCollectionFormMap' ).show();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).addClass('disabled');
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).css("pointer-events", "none");
  },

  endEditCancel: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .poiCollectionFormMap' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).removeClass('disabled');
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).css("pointer-events", "auto");

  },

  endEditSuccess: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .poiCollectionFormMap' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).removeClass('disabled');
    that.parent.$el.find( '.resourceLocationFrame .locationButtons .endEditCancel' ).css("pointer-events", "auto");        

  }

});
