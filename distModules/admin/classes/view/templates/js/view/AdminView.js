var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  childView: false,

  bodyClass: false,

  initialize: function(){
    this.renderMenu();
  },

  renderMenu: function(  ){
    //Categories
    var menuCategoryElement =  _.template($("#menuCategoryElement").html());
    var menuCategoriesDiv = $('#wrapper .navbar  .sidebar .categoriesList');

    menuCategoriesDiv.html( menuCategoryElement( { categories:  app.categories.toJSON()  } ) );
    //Topics
    var menuTopics =  _.template($("#menuTopics").html());
    var menuTopicsContainer = $('#wrapper #side-menu');
    menuTopicsContainer.prepend( menuTopics( { topics:  app.topics.toJSON()  } ) );
    //Starred
    var menuStarred =  _.template($("#menuStarred").html());
    var menuStarredContainer = $('#wrapper .navbar .sidebar .starredList');
    menuStarredContainer.prepend( menuStarred( { starred:  app.starred.toJSON()  } ) );
  },

  categoryEdit: function( id ) {
    this.childView = new CategoryEditorView( app.categories.get(id) );
    this.render(  );
  },

  starredList: function( id ){
    this.childView = new ResourcesStarredListView( app.starred.get(id) );
    this.render(  );
  },

  loadAjaxContent: function( url ) {
    var that=this;
    that.childView = false;
    $("#page-wrapper").load( url, {}, function(){ that.render(); } );

  },


  loadAjaxContentModal: function( modalUrl, modalId, modalTitle, size ) {

    if( size && size === "md" ){
      var modalTemplate = _.template( $('#modalMdTemplate').html() );
    }else{
      var modalTemplate = _.template( $('#modalLgTemplate').html() );
    }


    $('body').append(modalTemplate({ 'modalId': modalId, 'modalTitle': modalTitle }));
    $("#"+modalId+" .modal-body").load( modalUrl );
    $("#"+modalId).modal({
      'show' : true
    });

    //$("#"+modalId).on('shown.bs.modal', function (e) {alert('carga');});
    $("#"+modalId).on('hidden.bs.modal', function (e) {
      e.target.remove();
    });
    $(document).on('hidden.bs.modal', '.modal', function () {
      $('.modal:visible').length && $(document.body).addClass('modal-open');
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
    if( buttonsToMove.size() > 0 ){
      buttonsToMove.each( function() {
        var that = this;
        var cloneButtonTop = $(this).clone();
        var cloneButtonBottom = $(this).clone();
        if( !$(this).is('.btn, .btn-primary') ){
          cloneButtonTop.addClass( 'btn btn-primary' );
          cloneButtonBottom.addClass( 'btn btn-primary' );
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