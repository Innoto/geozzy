
var app = app || {};


$(window).ready(function(){
  var els = $('.switchery');
  els.each(function( index )  {
    var switchery = new Switchery( this, { color : '#58ba81', secondaryColor : '#FF6A4B'} );
  });

  bindResourceForm();
});

function bindResourceForm(){
  $('#resourceAddCollection').on('click', function(){
    //PARAMS( URL - ID - TITLE )
    app.mainView.loadAjaxContentModal('/admin/collection/create', 'resourceCollectionsModal', 'Create Collection');
  });
}
