var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSliderView = geozzy.filterView.extend({

    isTaxonomyFilter: true,
    slider: false,

    template: _.template(
      " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
      "<div class='<%= filterClass %>'>"+
        "<input type='text'> "+
      "</div>"
    ),
    templateOption: _.template("<option value='<%- id %>' icon='<%- icon %>'><%- name %></option>"),

    templateSummary: _.template(
      " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
      "<span class='<%= filterClass %>-Summary'><%= value %>€</span>"
    ),


    initialize: function( opts ) {
      var that = this;
      var options = {
        title: false,
        mainContainerClass: false,
        containerClass: false,
        titleSummary: false,
        summaryContainerClass: false,

        filteredValue: false,
        valueMin: 3,
        valueMax: 100,

      }



      that.options = $.extend(true, {}, options, opts);
    },

    filterAction: function( model ) {
      var that = this;
      var ret = true;


      var price =  model.get('averagePrice');

      if(typeof price != "undefined" ) {
        if( price <= that.options.filteredValue || that.options.filteredValue == false ) {
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
      if( !$(  that.options.mainContainerClass+' .' +that.options.containerClass ).length ) {
        $( that.options.mainContainerClass).append( '<div class="explorerFilterElement '+ that.options.containerClass +'">' + filterHtml + '</div>' );
      }
      else {

        $( that.options.mainContainerClass+' ' + containerClassDots ).html( filterHtml );
      }
      that.instanceSlider();

    },


    instanceSlider: function() {
      var that = this;
      $( ".explorerFilterElement ."+that.options.containerClass+" input" ).ionRangeSlider({
          type: "single",
          min: that.options.valueMin,
          max: that.options.valueMax,
          from: that.filteredValue,
          postfix: "€",
          keyboard: true,
          onStart: function (data) {
              //console.log("onStart");
          },
          onChange: function (data) {

          },
          onFinish: function (data) {

            that.options.filteredValue = data.from;
            that.parentExplorer.applyFilters();


            // Filter summaries
            if(that.options.summaryContainerClass) {
              that.renderSummary();
            }



          },
          onUpdate: function (data) {
              //console.log("onUpdate");
          }
      });
      that.slider = $( ".explorerFilterElement ."+that.options.containerClass+" input" ).data("ionRangeSlider");

    },

    renderSummary: function(  ) {
      var that = this;
      var containerClassDots = '.'+that.options.summaryContainerClass.split(' ').join('.');


      if(  that.options.filteredValue  ) {

        var summaryHtml = that.templateSummary( { filterClass: that.options.containerClass, title: that.options.titleSummary, value:  that.options.filteredValue   } );
        $( containerClassDots ).html( summaryHtml );

      }
      else {
        $( containerClassDots ).html( "" );
      }


    },


    reset: function() {

      var that = this;
      that.slider.reset();
      that.options.filteredValue = false;
      that.renderSummary();
      //that.slider.destroy();
      //$( ".explorerFilterElement ."+that.options.containerClass+" input" ).val(10);
      //that.instanceSlider();
    }

});
