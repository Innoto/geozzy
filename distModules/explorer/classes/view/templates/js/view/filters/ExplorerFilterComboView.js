var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterComboView = geozzy.filterView.extend({



  isTaxonomyFilter: true,
  template: _.template(
                          " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
                          "<select class='<%= filterClass %>'>"+
                            "<% if(defaultOption){ %> <option value='<%- defaultOption.value %>' icon='<%- defaultOption.icon %>'><%- defaultOption.title %></option> <%}%>"+
                            "<%= options %>"+
                          "</select>"
                      ),
  templateOption: _.template("<option value='<%- id %>' icon='<%- icon %>'><%- name_es %></option>"),

  initialize: function( opts ) {
    var that = this;

    var options = {
      title: false,
      mainCotainerClass: false,
      resumeContainerClass: false,
      containerClass: false,
      defaultOption: false,
      data: false
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
    if( !$(  that.options.mainCotainerClass+' .' +that.options.containerClass ).length ) {
      $( that.options.mainCotainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
    }
    else {

      $( that.options.mainCotainerClass+' ' + containerClassDots ).html( filterHtml );
    }


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


    // Filter Resumes
    if(that.resumeContainerClass) {
      that.renderResume();
    }

  },

  renderResume: function() {

  },

  reset: function() {
    var that = this;
    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
    $select = $( that.options.mainCotainerClass + ' ' + containerClassDots + ' select' );

    $select.val( "*" );

    that.selectedTerms = false;
  }

});
