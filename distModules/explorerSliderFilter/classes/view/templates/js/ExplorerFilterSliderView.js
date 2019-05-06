var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSliderView = geozzy.filterView.extend({

    isTaxonomyFilter: true,
    slider: false,

    template: _.template(
      " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
      "<div class='slider'>"+
        "<input type='text'> "+
      "</div>"
    ),
    templateOption: _.template("<option value='<%- id %>' icon='<%- icon %>'><%- name %></option>"),

    templateSummary: _.template(
      " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
      "<span class='slider-Summary'><%= value %>€</span>"
    ),


    initialize: function( opts ) {
      var that = this;
      var options = {
        title: false,

        elSummaryContainer:false,
        titleSummary: false,

        postfix: '€',
        keyToFilter: 'averagePrice',

        filteredValue: false,
        valueMin: 3,
        valueMax: 100,
        valuesGrid: false,
        type: 'single',  // (single|double)
        prettify: function(num) {return num;}
      };

      that.options = $.extend(true, {}, options, opts);
      that.$elSummaryContainer = $(that.options.elSummaryContainer);
    },

    filterAction: function( model ) {
      var that = this;
      var ret = true;

      var valueToFilter = [];

      if (Array.isArray( that.options.keyToFilter ) ) {
        valueToFilter[0] =  model.get(that.options.keyToFilter[0]);
        valueToFilter[1] =  model.get(that.options.keyToFilter[1]);
      }
      else {
        valueToFilter =  model.get(that.options.keyToFilter);
      }


      if( that.options.type === 'single'  && typeof valueToFilter != "undefined") {
        if (Array.isArray( valueToFilter ) ) {
          if( valueToFilter[0] < that.options.filteredValue && valueToFilter[1] > that.options.filteredValue) {
            ret = true;
          }
          else {
            ret = false;
          }
        }
        else {
          if( valueToFilter <= that.options.filteredValue || that.options.filteredValue == false ) {
            ret = true;
          }
          else
          {
            ret = false;
          }
        }

      }
      else
      if(  that.options.type === 'double'  && typeof valueToFilter != "undefined"  ) {
        if (Array.isArray( valueToFilter ) ) {

          if(typeof valueToFilter[0] == "undefined" || valueToFilter[0] == false) {
             valueToFilter[0] = that.options.filteredValue[0];
          }
          if(typeof valueToFilter[1] == "undefined" || valueToFilter[1] == false) {
             valueToFilter[1] = that.options.filteredValue[1];
          }

          if( valueToFilter[0] <= that.options.filteredValue[1] && valueToFilter[1] >= that.options.filteredValue[0]) {
            ret = true;
          }
          else {
            ret = false;
          }
        }
        else {
          if( (valueToFilter >= that.options.filteredValue[0] && valueToFilter <= that.options.filteredValue[1] ) || that.options.filteredValue == false ) {
            ret = true;
          }
          else
          {
            ret = false;
          }

        }

      }
      else{
        ret = true;
      }

      return ret;
    },

    render: function() {
      var that = this;
      var filterHtml = that.template( { title: that.options.title } );

      that.$el.html( filterHtml );
      that.instanceSlider();
    },


    instanceSlider: function() {
      var that = this;
      that.$el.find("input" ).ionRangeSlider({
          type: that.options.type,
          min: that.options.valueMin,
          max: that.options.valueMax,
          from: that.filteredValue,
          postfix: that.options.postfix,
          keyboard: true,
          grid: that.options.valuesGrid,
          prettify: function(num) { return that.options.prettify(num); },
          onStart: function (data) {
              //cogumelo.log("onStart");
          },
          onChange: function (data) {

          },
          onFinish: function (data) {

            if( that.options.type === 'single') {
              that.options.filteredValue = data.from;
            }
            else {
              that.options.filteredValue = [data.from, data.to];
            }



            that.parentExplorer.applyFilters();


            // Filter summaries
            if(that.options.elSummaryContainer) {
              that.renderSummary();
            }



          },
          onUpdate: function (data) {
              //cogumelo.log("onUpdate");
          }
      });
      that.slider = that.$el.find("input" ).data("ionRangeSlider");

    },

    renderSummary: function(  ) {
      var that = this;

      if(that.options.summaryContainer) {



        if(  that.options.filteredValue  ) {

          var summaryHtml = that.templateSummary( { title: that.options.titleSummary, value:  that.options.filteredValue   } );
          that.$elSummaryContainer.html( summaryHtml );

        }
        else {
          that.$elSummaryContainer.html( "" );
        }
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
