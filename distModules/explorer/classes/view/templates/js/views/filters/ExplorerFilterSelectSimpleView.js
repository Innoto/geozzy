var geozzy = geozzy || {};
if(!geozzy.filters) geozzy.filters={};

geozzy.filters.filterSelectSimpleView = geozzy.filterView.extend({

  template: _.template("<label><%= title %>:</label>  <select class='<%= filterClass %>'><%= options %></select>"),
  templateOption: _.template("<option value='<%- value %>'><%- title %></option>"),



  filterAction: function( model ) {
    var ret = false;

    if( this.selectedData != false ) {

      var terms =  model.get('terms');

      var diff = $( terms ).not( this.selectedData );

      //console.log(diff.length, terms.length)
      ret = (diff.length != terms.length );
    }
    else {
      ret = true;
    }

    return ret;
  },

  render: function() {

    var that = this;

    var filterOptions = '';

    $.each(that.data, function(i,e){
      filterOptions += that.templateOption(e);
    });

    var filterHtml = that.template( { filterClass: that.options.containerClass+'filtro1_select', title: that.title, options: filterOptions } );

    // Print filter html into div
    if( !$(  '.'+that.options.mainCotainerClass+' .' +that.options.containerClass ).length ) {
      $( '.'+that.options.mainCotainerClass).append( '<div class='+ that.options.containerClass +'>' + filterHtml + '</div>' );
    }
    else {
      $(  '.'+that.options.mainCotainerClass+' .' + that.options.containerClass ).html( filterHtml );
    }


    $('.'+that.options.containerClass+' select').bind('change', function(el) {
      var val = $(el.target).val();
      if( val == '*' ) {
        that.selectedData = false;
      }
      else {
        //that.selectedData = false;
        that.selectedData = [ parseInt( $(el.target).val() ) ];
      }

      that.parentExplorer.applyFilters();
    });


  }

});
