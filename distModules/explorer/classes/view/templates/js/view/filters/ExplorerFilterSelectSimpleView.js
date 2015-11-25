var geozzy = geozzy || {};
if(!geozzy.filters) geozzy.filters={};

geozzy.filters.filterSelectSimpleView = geozzy.filterView.extend({

  template: _.template(
                          " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
                          "<select class='<%= filterClass %>'>"+
                            "<% if(defaultOption){ %> <option value='<%- defaultOption.value %>' icon='<%- defaultOption.icon %>'><%- defaultOption.title %></option> <%}%>"+
                            "<%= options %>"+
                          "</select>"
                      ),
  templateOption: _.template("<option value='<%- id %>' icon='<%- icon %>'><%- name_es %></option>"),

  initialize: function( opts ) {
    this.options.defaultOption = false;

    $.extend(true, this.options, opts);

  },

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

    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');




    $.each(that.options.data.toJSON(), function(i,e){
      filterOptions += that.templateOption(e);
    });




    var filterHtml = that.template( { filterClass: that.options.containerClass, title: that.options.title, defaultOption: that.options.defaultOption, options: filterOptions } );

    // Print filter html into div
    if( !$(  that.options.mainCotainerClass+' .' +that.options.containerClass ).length ) {
      $( that.options.mainCotainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
    }
    else {

      $( that.options.mainCotainerClass+' ' + containerClassDots ).html( filterHtml );
    }


    $( that.options.mainCotainerClass + ' ' + containerClassDots + ' select').bind('change', function(el) {
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
