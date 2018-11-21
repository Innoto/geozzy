var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};
if(!geozzy.explorerComponents.filters) geozzy.explorerComponents.filters={};

geozzy.explorerComponents.filters.filterButtonsView = geozzy.filterView.extend({


  attributes: {},
  initialize: function(options){
    this.attributes = {};
  },

  isTaxonomyFilter: true,
  template: false,
  templateOption: false,

  initialize: function( opts ) {
    var that = this;

    var options = {
      template: geozzy.explorerComponents.filterButtonsViewTemplate,
      templateOption: geozzy.explorerComponents.filterButtonsViewOption,
      multiple:false,
      onChange: function(){}
    };

    that.options = $.extend(true, {}, options, opts);

    that.template = _.template( that.options.template );
    that.templateOption = _.template( that.options.templateOption  );
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
    var containerClassDots = '.'+that.options.containerClass.split(' ').join('.');
    $( that.options.mainContainerClass + ' ' + containerClassDots + ' ul li').removeClass('selected');
    that.selectedTerms = false;
  }

});
