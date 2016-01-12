var geozzy = geozzy || {};
if(!geozzy.filters) geozzy.filters={};

geozzy.filters.filterSelectImgView = geozzy.filterView.extend({


  attributes: {},
  initialize: function(options){
    this.attributes = {};
  },

  isTaxonomyFilter: true,
  template: _.template(
    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
    "<ul class='<%= filterClass %>'>"+
      "<% if(defaultOption){ %> "+
        "<li data-resourceid='<%- defaultOption.value %>' > "+
          "<div class='title'><%- defaultOption.title %></div> "+
          "<img class='icon' src='/cgmlImg/<%- defaultOption.icon %>/typeIcon/icon.png'> " +
          "<img class='iconHover' src='/cgmlImg/<%- defaultOption.icon %>/typeIconHover/iconHover.png'> " +
          "<img class='iconSelected' src='/cgmlImg/<%- defaultOption.icon %>/typeIconSelected/iconSelected.png'> " +
        "</li>"+
      "<%}%>"+
      "<%= options %>"+
    "</ul>"
  ),

  templateOption: _.template(
    "<li data-resourceid='<%- id %>'>"+
      "<div class='title'><%- name_es %></div> "+
      "<img class='icon' src='/cgmlImg/<%- icon %>/typeIcon/icon.png'> " +
      "<img class='iconHover' src='/cgmlImg/<%- icon %>/typeIconHover/iconHover.png'> " +
      "<img class='iconSelected' src='/cgmlImg/<%- icon %>/typeIconSelected/iconSelected.png'> " +
    "</li>"
  ),

  initialize: function( opts ) {
    var that = this;

    that.options = opts

  },

  filterAction: function( model ) {
    var ret = false;

    if( this.selectedTerms != false ) {

      var terms =  model.get('terms');

      var diff = $( terms ).not( this.selectedTerms );

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
/*

    $( that.options.mainCotainerClass + ' ' + containerClassDots + ' select').bind('change', function(el) {
      var val = $(el.target).val();
      if( val == '*' ) {
        that.selectedTerms = false;
      }
      else {
        //that.selectedTerms = false;
        that.selectedTerms = [ parseInt( $(el.target).val() ) ];
      }

      that.parentExplorer.applyFilters();
    });
*/

  

  }

});
