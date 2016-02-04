var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterButtonsView = geozzy.filterView.extend({


  attributes: {},
  initialize: function(options){
    this.attributes = {};
  },

  isTaxonomyFilter: true,
  template: _.template(
    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
    "<ul class='<%= filterClass %> clearfix'>"+
      "<% if(defaultOption){ %> "+
        "<li data-term-id='<%- defaultOption.value %>' > "+
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
    "<li data-term-id='<%- id %>'>"+
      "<div class='title'><%- name %></div> "+
      "<img class='icon' src='/cgmlImg/<%- icon %>/typeIcon/icon.png'> " +
      "<img class='iconHover' src='/cgmlImg/<%- icon %>/typeIconHover/iconHover.png'> " +
      "<img class='iconSelected' src='/cgmlImg/<%- icon %>/typeIconSelected/iconSelected.png'> " +
    "</li>"
  ),

  initialize: function( opts ) {
    var that = this;
    that.options = $.extend(true, {}, that.options, opts);
  },

  filterAction: function( model ) {
    var that = this;
    var ret = false;

    if( that.selectedTerms != false ) {

      var terms =  model.get('terms');

      if( typeof terms != "undefined") {
        var diff = $( terms ).not( that.selectedTerms );

        //console.log(diff.length, terms.length)
        ret = (diff.length != terms.length );
      }

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
    if( !$(  that.options.mainContainerClass+' .' +that.options.containerClass ).length ) {
      $( that.options.mainContainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
    }
    else {

      $( that.options.mainContainerClass+' ' + containerClassDots ).html( filterHtml );
    }



    $( that.options.mainContainerClass + ' ' + containerClassDots + ' ul li').bind('click', function(el) {
      var termid = false;
      var termLi = false;

      if( typeof $(el.target).attr('data-term-id') !== "undefined"){
        termLi = $(el.target)
        termid = termLi.attr('data-term-id');

      }
      else
      if( typeof $(el.target).parent().attr('data-term-id') !== "undefined" ) {
        termLi = $(el.target).parent();
        termid = termLi.attr('data-term-id');
      }

      if( termid == '*' ) {
        that.selectedTerms = false;
      }
      else {
        //that.selectedTerms = false;
        that.reset();
        that.selectedTerms = [ parseInt( termid ) ];
        termLi.addClass('selected');
      }

      that.parentExplorer.applyFilters();

    });


/*

    $( that.options.mainContainerClass + ' ' + containerClassDots + ' select').bind('change', function(el) {
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



  },

  reset: function() {

    var that = this;
    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
    $( that.options.mainContainerClass + ' ' + containerClassDots + ' ul li').removeClass('selected');
    that.selectedTerms = false;
  }

});
