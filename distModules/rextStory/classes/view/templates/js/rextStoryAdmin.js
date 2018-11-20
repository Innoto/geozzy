$( document ).ready( function() {
  rextStoryAdminJs.bindResourceForm();
} );

var rextStoryAdminJs = {
  bindResourceForm: function() {
    var that = this;
    $('select.cgmMForm-field-rExtStory_steps').multiList( {
      itemActions : [],
      placeholder: __('Select options')
    } );
  }
};
