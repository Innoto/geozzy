var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterGeoView = geozzy.filterView.extend({



  isTaxonomyFilter: true,
  template: _.template(
    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
    "<select class='<%= filterClass %>'>"+
      "<% if(defaultOption){ %> <option value='<%- defaultOption.value %>' icon='<%- defaultOption.icon %>'><%- defaultOption.title %></option> <%}%>"+
      "<%= options %>"+
    "</select>"
  ),

  templateOption: _.template(
    "<option value='<%- id %>' data-img='<%- dataImg %>' data-coords='<%- dataCoords %>'  ><%- name %></option>"
  ),

  templateSummary: _.template(
    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
    "<div class='<%= filterClass %>-Summary'>"+
      "<div class='icon'> <img class='icon' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- option.icon %>/typeIcon/icon.png'> </div>" +
      "<div class='name'> <%- option.name %> </div>" +
    "</div>"
  ),


  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      mainContainerClass: false,
      containerClass: false,
      titleSummary: false,
      summaryContainerClass: false,
      defaultOption: false,
      data: false,
      textReset: 'All',
      onChange: function( value ){}
    };

    that.options = $.extend(true, {}, options, opts);
  },

  filterAction: function( model ) {
    var ret = false;

    if( this.selectedTerms != false ) {

      var terms =  model.get('terms');
      if( typeof terms != "undefined") {
        var diff = $( terms ).not( this.selectedTerms );
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

      // Filter summaries
      if(that.options.summaryContainerClass) {
        var selectedOption =  false;

        if(typeof that.selectedTerms[0] != 'undefined') {
          selectedOption = that.options.data.get( that.selectedTerms[0] ).toJSON();
        }

        that.renderSummary( selectedOption );
      }

      that.options.onChange( val );
    });



    $( that.options.mainContainerClass + ' ' + containerClassDots + ' select').zonaMap({
      width: 358,
      height: 383,
      textReset: that.options.textReset,
      htmlIconArrow: '<i class="fa fa-caret-down"></i>',
      imgSrc: cogumelo.publicConf.media+'/module/rextAppZona/img/gal.svg',
      imgTransparent: cogumelo.publicConf.media+'/module/rextAppZona/img/transparent.png'
    });






  },

  renderSummary: function( selectedOption ) {
    var that = this;
    var containerClassDots = '.'+that.options.summaryContainerClass.split(' ').join('.');


    if( selectedOption ) {

      var summaryHtml = that.templateSummary( { filterClass: that.options.containerClass, title: that.options.titleSummary, option: selectedOption  } );
      $( containerClassDots ).html( summaryHtml );
    }
    else {
      $( containerClassDots ).html( "" );
    }


  },

  reset: function() {
    //console.log('gEO')
    /*
    var that = this;
    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
    $select = $( that.options.mainContainerClass + ' ' + containerClassDots + ' select' );

    $select.val( "*" );

    that.selectedTerms = false;
    that.renderSummary( false );*/
  }

});
