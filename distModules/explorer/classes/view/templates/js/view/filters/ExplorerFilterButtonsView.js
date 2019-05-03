var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterButtonsView = geozzy.filterView.extend({


  attributes: {},
  isTaxonomyFilter: true,
  template: false,
  templateOption: false,

  initialize: function( opts ) {
    var that = this;

    var options = {
      template: geozzy.explorerComponents.filterButtonsViewTemplate,
      templateOption: geozzy.explorerComponents.filterButtonsViewOption,
      multiple:false,
      elSummaryContainer:false,
      onChange: function(){}
    };

    that.options = $.extend(true, {}, options, opts);

    that.template = _.template( that.options.template );
    that.templateOption = _.template( that.options.templateOption  );
    that.$elSummaryContainer = $(that.options.elSummaryContainer);
  },

  filterAction: function( model ) {
    var that = this;
    var ret = false;

    if( that.selectedTerms != false ) {

      var terms =  model.get('terms');

      if( typeof terms != "undefined") {
        var diff = $( terms ).not( that.selectedTerms );
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

    that.$el.find(' ul li').bind('click', function(el) {
      var termid = false;
      var termLi = false;

      if( typeof $(el.target).attr('data-term-id') !== "undefined"){
        termLi = $(el.target);
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
        if( that.options.multiple === true ) {

          if( $.inArray( parseInt( termid ) , that.selectedTerms ) !== -1  ) {

            // remove element from array
            //_.without(that.selectedTerms, parseInt( termid ));

            delete  that.selectedTerms[$.inArray( parseInt( termid ) , that.selectedTerms )];
            that.selectedTerms = that.selectedTerms.filter(function (el) {
              return el != null;
            })
            termLi.removeClass('selected');
          }
          else {
            if(that.selectedTerms == false) {
              that.selectedTerms = [];
            }
            that.selectedTerms.push( parseInt( termid ) );
            termLi.addClass('selected');
          }

        }
        else {
          that.reset();
          that.selectedTerms = [ parseInt( termid ) ];
          termLi.addClass('selected');
        }

      }

      that.parentExplorer.applyFilters();


      that.options.onChange();
    });






  },

  reset: function() {

    var that = this;

    that.$el.find('ul li').removeClass('selected');
    that.selectedTerms = false;
  }

});
