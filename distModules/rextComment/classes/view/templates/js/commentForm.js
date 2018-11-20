$(document).ready(function(){
  if(commentTermID && suggestTermID){
    commentForm.initCtype([ {id: commentTermID ,show: ["rate"]}, {id: suggestTermID, show: ["suggestType"]} ]);
  }

  $("input.inputRating").rating({
    min: 0,
    max: 5,
    step: 1,
    size: 'xs',
    theme: 'krajee-fa',
    clearCaption: '',
    clearButton: '',
    defaultCaption: '',
    starCaptions: {
      1: '',
      2: '',
      3: '',
      4: '',
      5: ''
    },
    filledStar:'<i class="fas fa-star" aria-hidden="true"></i>',
    emptyStar:'<i class="far fa-star" aria-hidden="true"></i>'
  });

});

var commentForm = {
  initCtype: function( param ) {
    var that = this;
    that.hideFields(param);
    that.showFields(that.searchCtypeData( param, $('input.cgmMForm-field-type[name=type]:checked').val() ) );
    $('input.cgmMForm-field-type').change( function() {
      that.hideFields(param);
      that.showFields(that.searchCtypeData( param, $('input.cgmMForm-field-type[name=type]:checked').val() ) );
    } );
  },

  searchCtypeData: function( param , id ) {
    var that = this;
    return $.grep( param, function( e ){
      return e.id == id;
    } )[0];
  },

  hideFields: function( param ) {
    var that = this;
    cogumelo.log(param);
    $.each(param, function( index, item ) {
      $.each(item.show, function( i, e ) {
        $('.cgmMForm-wrap.cgmMForm-field-'+e).hide();
      } );
    } );
  },

  showFields: function( param ) {
    var that = this;
    $.each(param.show, function( index, item ) {
      $('.cgmMForm-wrap.cgmMForm-field-'+item).show();
    } );
  }
};
