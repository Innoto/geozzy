var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  childView: false,

  bodyClass: false,

  chartsLoaded: false,

  initialize: function(){
    this.renderMenu();
  },

  renderMenu: function(  ){
    //Categories
    if($('#wrapper #menu-wrapper .sidebar .categoriesList').length > 0){
      var menuCategoryElement =  _.template($("#menuCategoryElement").html());
      var menuCategoriesDiv = $('#wrapper #menu-wrapper .sidebar .categoriesList');
      var menuCategoriesData = app.categories.toJSON();
      if(menuCategoriesData.length === 0){
        $('#side-menu .categories').hide();
      }else{
        menuCategoriesDiv.html( menuCategoryElement( { categories: menuCategoriesData } ) );
      }
    }
    //Topics
    var menuTopics =  _.template($("#menuTopics").html());
    var menuTopicsContainer = $('#wrapper #side-menu');
    menuTopicsContainer.prepend( menuTopics( { topics:  app.topics.toJSON()  } ) );


    //Starred
    if($('#wrapper #menu-wrapper .sidebar .starredList').length > 0){
      var menuStarred =  _.template($("#menuStarred").html());
      var menuStarredContainer = $('#wrapper #menu-wrapper .sidebar .starredList');
      var menuStarredData = app.starred.toJSON();
      if(menuCategoriesData.length === 0){
        $('#side-menu .starred').hide();
      }else{
        menuStarredContainer.prepend( menuStarred( { starred:  menuStarredData  } ) );
      }
    }
  },

  categoryEdit: function( id ) {
    this.childView = new CategoryEditorView( app.categories.get(id) );
    this.render(  );
  },

  menuEdit: function(){
    this.childView = new MenuEditorView();
    this.render();
  },

  starredList: function( id ){
    this.childView = new ResourcesStarredListView( app.starred.get(id) );
    this.render(  );
  },

  loadAjaxContent: function( url ) {
    var that=this;
    that.childView = false;
    $("#page-wrapper").load( url, {}, function(){
      that.render();
      $( window ).scrollTop( 0 );
      adminFileUploader.iniciaUploader();
    } );

  },

  loadAjaxCharts: function( url ) {
    var that=this;
    that.childView = false;

    if (that.chartsLoaded == false){
      $("#page-wrapper").load( url, {}, function(){
        that.render();
        that.chartsLoaded = $('#page-wrapper').html();
      });

    }else{
      $('#page-wrapper').html(that.chartsLoaded);
    }
  },

  loadAjaxContentModal: function( modalUrl, modalId, modalData, size ) {
    var modalTemplate = '';
    if( size && size === "md" ){
      modalTemplate = _.template( $('#modalMdTemplate').html() );
    }else{
      modalTemplate = _.template( $('#modalLgTemplate').html() );
    }


    $('body').append(modalTemplate({ 'modalId': modalId, 'modalTitle': modalData.title }));
    $("#"+modalId+" .modal-body").load( modalUrl, modalData );
    $("#"+modalId).modal({
      'show' : true
    });

    //$("#"+modalId).on('shown.bs.modal', function (e) {alert('carga');});
    $("#"+modalId).on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      if($('.modal:visible').length){
        $(document.body).addClass('modal-open');
      }
    });

  },

  render: function( ) {
    var that = this;

    if( that.childView !== false ) {
      $("#page-wrapper").undelegate();
      that.childView.$el = $('#page-wrapper');
      that.childView.render();
      that.childView.delegateEvents();
    }

    /* Busca los botones de los formularios externos por la class "gzzAdminToMove" los clona en el interface de admin.*/
    var buttonsToMove = $('.gzzAdminToMove');
    if( buttonsToMove.length > 0 ){
      buttonsToMove.each( function() {
        var that = this;
        var cloneButtonTop = $(this).clone(true, true);
        var cloneButtonBottom = $(this).clone(true, true);
        if( !$(this).is('.btn, .btn-primary') ){
          cloneButtonTop.addClass( 'btn btn-primary' );
          cloneButtonBottom.addClass( 'btn btn-primary' );
        }

        if($(this).attr('data-href')){
          cloneButtonTop.on( 'click', function(){
            window.location = $(this).attr('data-href');
          });
          cloneButtonBottom.on( 'click', function(){
            window.location = $(this).attr('data-href');
          });
        }

        cloneButtonTop.appendTo( ".headSection .headerActionsContainer" );
        cloneButtonBottom.appendTo( ".footerSection .footerActionsContainer" );
        $(this).hide();
      });
    }

    /* Bindeamos unha acción da táboa ao botón da barra superior */
    var pathname = window.location.href;
    parts1 = pathname.split('admin#');

    if (parts1[1]){
      parts2 = parts1[1].split('/');
      if (parts2[0] == 'resourceouttopic'){ //táboa de asignación intermedia recursos-temáticas
        // Assign
        $('.btnAssign').bind('click', function(){
          cogumeloTables.AdminViewResourceOutTopic.actionOnSelectedRows('assign', function() {
            window.location = 'admin#resourceintopic/list/'+parts2[2];
          });
        });
      }

      if (parts2[0] == 'starred' && parts2[3] == 'assign'){ //táboa de asignación intermedia recursos-destacados
        // Assign
        $('.btnAssign').bind('click', function(){
          cogumeloTables.AdminViewStarred.actionOnSelectedRows('assign', function(){window.location = 'admin#starred/star/'+parts2[2]+'/assign';});
        });
      }
    }
  },


  // effects
  menuSelect: function( menuClass ) {
    $('#side-menu *').removeClass('active');
    $('#side-menu .'+menuClass+' a').addClass('active');

    $('.navbar-collapse').collapse('hide');
  },

  setBodyClass: function( name ) {
    if( this.bodyClass ) {
      $('body').removeClass( this.bodyClass );
    }

    $('body').addClass( name );
    this.bodyClass = name;

  }

});
