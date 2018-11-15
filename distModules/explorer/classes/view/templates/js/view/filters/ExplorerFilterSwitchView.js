var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterSwitchView = geozzy.filterView.extend({

  filterValue: null,

  template: _.template(
    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
    "<div class='isSwitch'>"+
      "<input type='checkbox'  <% if(checked) {%> checked <%}%>/>"+
    "</div>"
  ),


  templateSummary: _.template(
    " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
    "<span class='<%= filterClass %>-Summary'><%= value %>€</span>"
  ),


  initialize: function( opts ) {
    var that = this;
    var options = {
      title: false,
      elSummaryContainer:false,
      titleSummary: false,

      defaultValue: null,
      keyToFilter: false,
      onChange: function() {}
    };

    that.options = $.extend(true, {}, options, opts);
    that.filterValue = that.options.defaultValue;
    that.$elSummaryContainer = $(that.options.elSummaryContainer);
  },

  filterAction: function( model ) {
    var that = this;
    var ret = true;

    if( that.options.keyToFilter !== false ) {
      var isRoute =  model.get(that.options.keyToFilter);

      if( parseInt(isRoute)  ) {
        isRoute = false;
      }
      else {
        isRoute = true;
      }


      if( that.filterValue !== null ) {
        if( that.filterValue == isRoute ) {
          ret = false;
        }
        else
        {
          ret = true;
        }
      }
      else{
        ret = true;
      }
    }
    else {
      ret = true;
    }

    return ret;
  },

  render: function() {
    var that = this;

    var filterHtml = that.template( { title: that.options.title, checked: that.filterValue } );
    that.$el.html( filterHtml );
    var init = new Switchery( that.$el.find('input')[0] );
    $( that.$el.find('input' )[0]).change( function( ev ) {
      that.filterValue = $( that.$el.find('input' )[0] ).is(":checked");
      that.parentExplorer.applyFilters();
      that.options.onChange();
    });

  },


  renderSummary: function(  ) {
    var that = this;
  },


  reset: function() {
    var that = this;

    that.filterValue = that.options.defaultValue;
    that.render();
    that.renderSummary();
    //that.slider.destroy();
    //$( ".explorerFilterElement ."+that.options.containerClass+" input" ).val(10);
    //that.instanceSlider();
  }

});
