
(function($) {



  $.fn.multiList = function( options ){
    var that = this;

console.log(this);
    var dataSelected = [];
    var selector = this;
    var selectedOptions = $(this).find('option:selected');
    var nestableContainer = $('.asign2selected');

    var defaults = {

    };
    var settings = $.extend( {}, defaults, options );

    that.getSelectedValues = function( ){
  console.log("getSelectedValues");
  console.log(selectedOptions);
      selectedOptions.each(function( index ) {
        dataSelected.push({
          id: $( this ).val(),
          value: $( this ).val(),
          weight: $( this ).attr('data-multilist-weight')
        });
      });
    }

    that.execNestable = function( ){
  console.log("execNestable");
  console.log(nestableContainer);
      nestableContainer.nestable({
        maxDepth: 1
      });
    }
    that.execSelect2 = function( ){
  console.log("execSelect2");
  console.log(selector);
      selector.select2({
        tags: "true",
        placeholder: "Select an option"
      });
    }
    that.init = function(){
      that.getSelectedValues();
      that.execSelect2();
      that.execNestable();
    }


    that.init();
  };




}( jQuery ));
