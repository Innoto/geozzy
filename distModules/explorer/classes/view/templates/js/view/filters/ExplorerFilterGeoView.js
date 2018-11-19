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
      elSummaryContainer:false,
      titleSummary: false,
      defaultOption: false,
      data: false,
      textReset: 'All',
      onChange: function( value ){}
    };

    that.options = $.extend(true, {}, options, opts);
    that.$elSummaryContainer = $(that.options.elSummaryContainer);
  },

  filterAction: function( model ) {
    var ret = false;

    if( this.selectedTerms != false ) {

      var terms =  model.get('terms');
      if( typeof terms != "undefined") {
        var diff = $( terms ).not( this.selectedTerms );
        //cogumelo.log(diff.length, terms.length);
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

    $.each(that.options.data.toJSON(), function(i,e){
      filterOptions += that.templateOption(e);
    });

    var filterHtml = that.template( { title: that.options.title, defaultOption: that.options.defaultOption, options: filterOptions } );

    that.$el.html( filterHtml );
    $that.$el.find('select').bind('change', function(el) {

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
      if( that.options.elSummaryContainer ) {
        var selectedOption =  false;

        if(typeof that.selectedTerms[0] != 'undefined') {
          selectedOption = that.options.data.get( that.selectedTerms[0] ).toJSON();
        }
        that.renderSummary( selectedOption );
      }

      that.options.onChange( val );
    });


    that.$el.find('select').zonaMap({
      width: 358,
      height: 383,
      textReset: that.options.textReset,
      htmlIconArrow: '<i class="fas fa-caret-down"></i>',
      imgSrc: cogumelo.publicConf.media+'/module/rextAppZona/img/gal.svg',
      imgTransparent: cogumelo.publicConf.media+'/module/rextAppZona/img/transparent.png'
    });
  },

  renderSummary: function( selectedOption ) {
    var that = this;

    if( selectedOption ) {
      var summaryHtml = that.templateSummary( { title: that.options.titleSummary, option: selectedOption  } );
      that.$elSummaryContainer.html( summaryHtml );
    }
    else {
      that.$elSummaryContainer.html( "" );
    }
  },

  reset: function() {
    //cogumelo.log('gEO');
    /*
    var that = this;
    $select = $( that.options.containerClass + ' select' );

    $select.val( "*" );

    that.selectedTerms = false;
    that.renderSummary( false );*/
  }

});
