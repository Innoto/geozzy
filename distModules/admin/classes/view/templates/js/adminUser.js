
$(document).ready(function(){

  $('input.switchery, input[data-switcheryEnable]').each( function( index )  {
    this.switcheryObj = new Switchery( this, { color : '#58ba81', secondaryColor : '#FF6A4B'} );
  });

  $('select.gzzMultiList').multiMultiList({
    orientation: 'horizontal',
    icon: '<i class="fa fa-arrows"></i>',
    placeholder: __('Select options')
  });
  $('select.gzzSelect2').select2();
});
