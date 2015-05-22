var app = app || {};

var AdminView = Backbone.View.extend({
  el : $("#wrapper"),

  childView: false,

  initialize: function(){
    this.renderMenu();
  },

  renderMenu: function(  ){
    //Categories
    var menuCategoryElement =  _.template($("#menuCategoryElement").html());
    var menuCategoriesDiv = $('#wrapper .navbar .navbar-default.sidebar .categoriesList');
    menuCategoriesDiv.html( menuCategoryElement( { categories:  app.categories.toJSON()  } ) );
    //Topics
    var menuTopics =  _.template($("#menuTopics").html());
    var menuTopicsContainer = $('#wrapper #side-menu');
    menuTopicsContainer.prepend( menuTopics( { topics:  app.topics.toJSON()  } ) );
  },

  categoryEdit: function( id ) {
    this.childView = new CategoryEditorView( app.categories.get(id) );
    this.render(  );
  },

  loadAjaxContent: function( url ) {
    var that=this;
    that.childView = false;
    $("#page-wrapper").load( url, {}, function(){ that.render(); } );

  },

  render: function( ) {
    var that = this;

    if( that.childView != false ) {
      $("#page-wrapper").undelegate();
      that.childView.$el = $('#page-wrapper');
      that.childView.render();
      that.childView.delegateEvents();
    }

    /* Busca los botones de los formularios externos por la class "gzzAdminToMove" los clona y los bindea en el interface de admin.*/
    var buttonsToMove = $('.gzzAdminToMove');
    if( buttonsToMove.size() > 0 ){
      buttonsToMove.each( function() {
        var that = this;
        var cloneButtonTop = $(this).clone();
        var cloneButtonBottom = $(this).clone();
        if(!$(this).is('.btn, .btn-primary')){
          cloneButtonTop.addClass('btn  btn-primary');
          cloneButtonBottom.addClass('btn  btn-primary');
        }
        cloneButtonTop.appendTo( ".headSection .headerActionsContainer" ).on( 'click', function (){
          $(that).closest('form').submit();
        });
        cloneButtonBottom.appendTo( ".footerSection .footerActionsContainer" ).on( 'click', function (){
          $(that).closest('form').submit();
        });
        $(this).hide();
      });
    }

  },

  // effects
  menuSelect: function( menuClass ) {
    $('#side-menu *').removeClass('active');
    $('#side-menu .'+menuClass+' a').addClass('active');

    $('.navbar-collapse').collapse('hide');

  }

});
