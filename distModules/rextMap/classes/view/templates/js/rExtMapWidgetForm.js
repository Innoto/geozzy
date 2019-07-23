var geozzy = geozzy || {};

/*
  HTML example to add a form map
*/
geozzy.rExtMapWidgetForm  = Backbone.View.extend({

  mapConteiner: '.mapContainer',
  dialogConteiner: '.mapContainer',
  mapReady: false,
  mapObject: false,
  toolBarObject: false,
  components: [],
  events: {
    "click .buttonEditLocation": "changeViewMode",
    "click  .resourceLocationColumn .locationButtons  .endEditCancel": "endEditCancelComponents",
    "click .resourceLocationColumn .locationButtons  .endEditSuccess": "endEditSuccessComponents",

  },

  initialize: function() {
    var that = this;
    that.initializeMap();
  },

  initializeMap: function(){
    var that = this;

    that.$el.append(
      '<div class="resourceLocationFrame">'+
      ' <div class="resourceLocationMap"></div>' +
      ' <div class="resourceLocationColumn">'+
      '  <div class="searchBox"><input class="address"><div class="automaticBtn"><i class="fas fa-crosshairs"></i></div></div>' +
      '  <div class="locationToolBox"></div>' +
      '  <div class="locationButtons" style="display:none;">'+
      '     <div class="editIcon"> </div> '  +
      '     <div class="btnActionsContainer">'+
      '       <div class="endEditCancel btn btn-warning">Cancel</div> ' +
      '       <div class="endEditSuccess btn btn-primary">Ok</div> </div>' +
      '     </div>'+
      '  <div class="locationDialog"></div>' +
      ' </div>' +
      '</div>'
    );

    var latInit = 0;
    var lonInit = 0;
    var zoomInit = 3;

    if(typeof cogumelo.publicConf.admin.adminMap === 'undefined') {
      cogumelo.log('cogumelo.publicConf.admin.adminMap is not defined in conf');
    }
    else {
      latInit = Number(cogumelo.publicConf.admin.adminMap.defaultLat);
      lonInit = Number(cogumelo.publicConf.admin.adminMap.defaultLon);
      zoomInit = Number(cogumelo.publicConf.admin.adminMap.defaultZoom);
    }

    // gmaps init
    var mapOptions = {
      center: { lat: latInit, lng: lonInit },
      zoom: zoomInit,
      scrollwheel: false,
      mapTypeControl: false,
      fullscreenControl: false,
      streetViewControl: false
    };
    that.mapObject = new google.maps.Map( that.$el.find('.resourceLocationMap')[0], mapOptions);
    google.maps.event.addListenerOnce( that.mapObject ,'idle',function(e) {
      that.mapReady = true;
    });
    that.setCustomMapControls();
  },

  renderDialog: function(content) {
    var that = this;
    that.$el.find( that.dialogConteiner );
  },

  addToolBarbutton: function( button ) {
    var that = this;

    that.$el.find('.locationToolBox').append(
      '<div class="button" idToolButton="'+button.id+'">' +
      ' <div class="toolboxIcon icon">'+button.icon+'</div>'+
      '</div>'
    );

    var btDiv = that.$el.find('.locationToolBox').find( ".button[idToolButton='" + button.id + "']" );
    btDiv.on( 'click', function(ev) {
      that.$el.find('.resourceLocationColumn  .locationToolBox').hide();
      that.$el.find( '.resourceLocationColumn .locationButtons .editIcon' ).html(button.icon);
      that.$el.find( '.resourceLocationFrame .locationButtons' ).show();
      button.onclick();
    });

  },



  changeViewMode: function() {
    var that =this;
    if( that.$el.closest('.card').hasClass('locationFullScreen') ) {
      that.endEditCancelComponents();
    }

    that.$el.closest('.card').toggleClass( 'locationFullScreen' );


  },

  addComponent: function( component ) {
    var that = this;

    component.parent = that;
    that.components.push( component );

    if( that.mapReady == true ){
      component.render();
    }
    else {
      google.maps.event.addListenerOnce( that.mapObject ,'idle',function(e) {
        component.render();
      });
    }

  },

  endEditCancelComponents: function() {
    var that = this;
    that.$el.find('.resourceLocationColumn  .locationToolBox').show();
    $.each(that.components, function(i,e){
      if( e.editing == true ) {
        e.endEditCancel();
      }
    });
  },

  endEditSuccessComponents: function() {
    var that = this;
    that.$el.find('.resourceLocationColumn  .locationToolBox').show();
    $.each(that.components, function(i,e){
      e.endEditSuccess();
    });
  },



  //
  //  Map controls admin
  //
  controlMapSat:'',
  controlMapRel:'',
  controlMapMap: '',

  setCustomMapControls: function() {
    var that = this;
    var controlsMapsContainer = document.createElement('div');
    $(controlsMapsContainer).addClass('controlMapMainContainer');
    // Set CSS for the control border.
    that.controlMapSat = document.createElement('div');
    $(that.controlMapSat).addClass('controlMapSat controlMapCustom');
    $(that.controlMapSat).hide();
    controlsMapsContainer.appendChild(that.controlMapSat);

    that.controlMapMap = document.createElement('div');
    $(that.controlMapMap).addClass('controlMapMap controlMapCustom');
    $(that.controlMapMap).hide();
    controlsMapsContainer.appendChild(that.controlMapMap);


    that.controlMapRel = document.createElement('div');
    $(that.controlMapRel).addClass('controlMapRel controlMapCustom');
    $(that.controlMapRel).hide();
    controlsMapsContainer.appendChild(that.controlMapRel);

    //
    that.controlMapSat.addEventListener('click', function() {
      that.mapObject.setMapTypeId(google.maps.MapTypeId.SATELLITE);
      that.controlMapChoose('satellite');
    });

    that.controlMapMap.addEventListener('click', function() {
      that.mapObject.setMapTypeId(google.maps.MapTypeId.ROADMAP);
      that.controlMapChoose('roadmap');
    });

    that.controlMapRel.addEventListener('click', function() {
      that.mapObject.setMapTypeId(google.maps.MapTypeId.TERRAIN);
      that.controlMapChoose('terrain');
    });

    google.maps.event.addListenerOnce(that.mapObject, 'idle', function() {
      that.mapObject.controls[google.maps.ControlPosition.LEFT_BOTTOM].push(controlsMapsContainer);
      setTimeout(function(){
        that.controlMapChoose(that.mapObject.getMapTypeId());
      },2000);
    });

    google.maps.event.addListener(that.mapObject, 'mouseup', function() {
      that.controlMapChoose(that.mapObject.getMapTypeId());
    });

  },

  controlMapChoose: function(activeClass){
    var that = this;

    $(that.mapObject.getDiv() ).find('.controlMapCustom').hide();
    if( activeClass !== 'satellite' ){
      $(that.mapObject.getDiv() ).find(that.controlMapSat).show();
    }
    if( activeClass !== 'roadmap' ){
      $(that.mapObject.getDiv() ).find(that.controlMapMap).show();
    }
    if( activeClass !== 'terrain' ){
      $(that.mapObject.getDiv() ).find(that.controlMapRel).show();
    }
  }


});
