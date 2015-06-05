
(function($) {



  $.fn.multiList = function( options ){
    var that = this;

    var multiListContainer;
    var multiListNestable;
    var multiListSelect2;
    var dataSelected = [];
    var selector = this;

    var defaults = {

    };
    var settings = $.extend( {}, defaults, options );

    that.createInterface = function (){
      var multiListHtml = "";
      multiListHtml+= '<div class="multiListContainer">';
        multiListHtml+= '<div class="multiListNestable dd"><ol class="dd-list clearfix"></ol></div>';
        multiListHtml+= '<div class="multiListSelect2"></div>';
      multiListHtml+= '</div>';

      $(this).before( multiListHtml );
      multiListContainer = $('.multiListContainer');
      multiListNestable = multiListContainer.find('.multiListNestable');
      multiListSelect2 = multiListContainer.find('.multiListSelect2');
      multiListSelect2.append( selector );

    },

    that.getSelectedValues = function( ){
      dataSelected = [];
      selector.find('option:selected').each(function( index ) {
        dataSelected.push({
          id: $( this ).val(),
          name: $( this ).text(),
          weight: $( this ).attr('data-multilist-weight')
        });
      });

      dataSelected.sort(function(a,b) { return parseInt(a.weight) - parseInt(b.weight) } );
    }

    that.execNestable = function( ){
      multiListNestable.find('.dd-list').html('');
console.log(dataSelected);
      if( dataSelected.length > 0 ){
        $.each( dataSelected, function( key, elem ) {
          var nestableItem = '<li class="dd-item" data-id="'+elem.id+'">';
          nestableItem += '<div class="unselectNestable">X</div>';
          nestableItem += '<div class="dd-handle">'+elem.name+'</div>';
          nestableItem += '</li>';
console.log(multiListNestable);

          multiListNestable.find('.dd-list').append(nestableItem);
        });
      }
      multiListNestable.nestable({
        maxDepth: 1
      });
    }
    that.execSelect2 = function( ){
      selector.select2({
        tags: "true",
        placeholder: "Select an option"
      });
    }
    that.multiListBinds = function( ){
      selector.on("change", function (e) {
        that.getSelectedValues();
        that.execNestable();
      });
    }
    that.init = function(){
      that.getSelectedValues();
      that.createInterface();
      that.execSelect2();
      that.execNestable();
      that.multiListBinds();
    }
    that.init();
  };

}( jQuery ));
