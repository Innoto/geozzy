var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSliderView = geozzy.filterView.extend({

    isTaxonomyFilter: true,
    slider: false,
    filteredValue: false,
    valueMin: 3,
    valueMax: 100,
    firstValue: 100,
    template: _.template(

                    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
                    "<div class='<%= filterClass %>'>"+
                      "<input type='text'> "+
                    "</div>"
                  ),
    templateOption: _.template("<option value='<%- id %>' icon='<%- icon %>'><%- name_es %></option>"),

    templateSummary: _.template(
      " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
      "<div class='<%= filterClass %>-Summary'><%= value %>€</div>"
    ),


    initialize: function( opts ) {
      var that = this;
      var options = {
        title: false,
        mainCotainerClass: false,
        containerClass: false,
        titleSummary: false,
        summaryContainerClass: false,
        values:  []
      }



      that.options = $.extend(true, {}, options, opts);
    },

    filterAction: function( model ) {
      var that = this;
      var ret = true;


      var price =  model.get('averagePrice');

      if(typeof price != "undefined" ) {
        if( price <= that.filteredValue) {
          ret = true;
        }
        else
        {
          ret = false;
        }
      }
      else{
        ret = true;
      }



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
      that.instanceSlider();

    },


    instanceSlider: function() {
      var that = this;
      $( ".explorerFilterElement ."+that.options.containerClass+" input" ).ionRangeSlider({
          type: "single",
          min: that.valueMin,
          max: that.valueMax,
          from: that.firstValue,
          postfix: "€",
          keyboard: true,
          onStart: function (data) {
              //console.log("onStart");
          },
          onChange: function (data) {

          },
          onFinish: function (data) {

            that.filteredValue = data.from;
            that.parentExplorer.applyFilters();


            // Filter summaries
            if(that.options.summaryContainerClass) {
              that.renderSummary( that.filteredValue );
            }



          },
          onUpdate: function (data) {
              //console.log("onUpdate");
          }
      });
      that.slider = $( ".explorerFilterElement ."+that.options.containerClass+" input" ).data("ionRangeSlider");

    },

    renderSummary: function( filteredValue ) {
      var that = this;
      var containerClassDots = '.'+that.options.summaryContainerClass.split(' ').join('.');


      if( filteredValue ) {

        var summaryHtml = that.templateSummary( { filterClass: that.options.containerClass, title: that.options.titleSummary, value: filteredValue  } );
        $( containerClassDots ).html( summaryHtml );
        
      }
      else {
        $( containerClassDots ).html( "" );
      }


    },


    reset: function() {
      var that = this;

      that.slider.reset();
      //that.slider.destroy();
      //$( ".explorerFilterElement ."+that.options.containerClass+" input" ).val(10);
      //that.instanceSlider();
    }

});
