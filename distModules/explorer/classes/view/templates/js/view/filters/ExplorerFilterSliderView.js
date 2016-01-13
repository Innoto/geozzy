var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSliderView = geozzy.filterView.extend({

    isTaxonomyFilter: true,
    template: _.template(

                    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
                    "<div class='<%= filterClass %>'>"+
                      //"<% if(defaultOption){ %> <option value='<%- defaultOption.value %>' icon='<%- defaultOption.icon %>'><%- defaultOption.title %></option> <%}%>"+
                      //"<%= options %>"+
                      "<input type='text'> "+
                    "</div>"
                  ),
    templateOption: _.template("<option value='<%- id %>' icon='<%- icon %>'><%- name_es %></option>"),


    initialize: function( opts ) {
      var that = this;
      that.options = opts
    },

    filterAction: function( model ) {
      var ret = true;


/*
      if( this.selectedTerms != false ) {

        var terms =  model.get('terms');

        var diff = $( terms ).not( this.selectedTerms );

        //console.log(diff.length, terms.length)
        ret = (diff.length != terms.length );
      }
      else {
        ret = true;
      }
*/

      return ret;
    },

    render: function() {
      var that = this;

      var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
      var filterHtml = that.template( { filterClass: that.options.containerClass, title: that.options.title } );

      // Print filter html into div
      if( !$(  that.options.mainCotainerClass+' .' +that.options.containerClass ).length ) {
        $( that.options.mainCotainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
      }
      else {

        $( that.options.mainCotainerClass+' ' + containerClassDots ).html( filterHtml );
      }

      $( ".explorerFilterElement ."+that.options.containerClass+" input" ).ionRangeSlider({
          type: "single",
          min: 0,
          max: 100,
          from: 50,
          keyboard: true,
          onStart: function (data) {
              console.log("onStart");
          },
          onChange: function (data) {
              console.log("onChange");
          },
          onFinish: function (data) {
              console.log("onFinish");
          },
          onUpdate: function (data) {
              console.log("onUpdate");
          }
      });

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
