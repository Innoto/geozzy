var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.activeListView = Backbone.View.extend({

  tpl: false,
  tplElement: false,

  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],

  currentPage: 0,

  events: {

      // resource events

      "click .explorerActiveListContent .accessButton": "resourceClick",
      "touchend .explorerActiveListContent .element": "resourceTouch",
      "mouseenter .explorerActiveListContent .element": "resourceHover",
      "mouseleave .explorerActiveListContent .element": "resourceOut",
  },

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      showInBuffer: true,
      showOutMapAndBuffer: false,
      cateogories: false,

      endPage: 3,
      totalPages: false,

      tpl: geozzy.explorerComponents.activeListViewTemplate,
      tplElement: geozzy.explorerComponents.activeListViewElement
    });

    that.options = $.extend(true, {}, options, opts);

    that.tpl = _.template(that.options.tpl);
    that.tplElement = _.template(that.options.tplElement);
  },


  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },


  getVisibleResourceIds: function() {
    var that = this;
    this.parentExplorer.resourceIndex.removePagination();

    var visibleResources = that.parentExplorer.resourceIndex.setPerPage(30);


    visibleResources.setSort('mapVisible', 'desc');

    // get total packages
    that.options.totalPages = that.parentExplorer.resourceIndex.getNumPages();

    // set current page
    visibleResources.setPage(that.currentPage);

    that.visibleResources = visibleResources.pluck( 'id' );
    return visibleResources.pluck( 'id' );
  },


  render: function() {
    var that = this;


    that.$el.html('');
    var contador = 1;



    var contentHtml = '';
    $.each(  this.visibleResources, function(i,e){

      //that.options.categories
    //  console.log( that.parentExplorer.resourceMinimalList.toJSON/ )

      var elementCategory = false;

      that.options.categories.each( function(e2){
        //console.log(e.get('id'))
        //console.debug(markerData.get('terms'))

        if( $.inArray(e2.get('id'), that.parentExplorer.resourceMinimalList.get( e ).get('terms')  ) > -1 ) {

          elementCategory = e2;
          if(e2) {
            elementCategory = e2.toJSON()
          }
          return false;
/*
          if( jQuery.isNumeric( e2.get('icon') )  ){

            return false;
          }*/

        }

      });


      var minJSON = that.parentExplorer.resourceMinimalList.get( e ).toJSON();
      var partJSON = that.parentExplorer.resourcePartialList.get( e ).toJSON();

      var element = $.extend( true, partJSON, minJSON );

      element.contador = contador;
      element.category = elementCategory;

      // metrics
      that.parentExplorer.metricsResourceController.eventPrint(
        that.parentExplorer.resourcePartialList.get( e ).get('id'),
        'Explorer: '+that.parentExplorer.options.explorerSectionName
      );

      contentHtml += that.tplElement(element);
      contador++;
    });


    that.$el.html( that.tpl({ content: contentHtml }) )

  },



  setPage: function( pageNum ) {
    var that = this;

    // Set page number according limits and enable pages
    if( pageNum < 0 ) {
      pageNum = 0;
    }

    if( that.options.endPage != false && pageNum > that.options.endPage ) {

      pageNum = that.options.endPage;

    }
    else
    if ( that.options.endPage != false && pageNum > that.options.totalPages ){
      pageNum = that.options.totalPages;
    }
    else
    if( that.options.endPage == false &&  pageNum > that.options.totalPages  ){
      pageNum = that.options.endPage
    }

    that.currentPage = pageNum;

    //fetch new visible elements from explorer
    that.parentExplorer.fetchPartialList(
      that.getVisibleResourceIds(),
      function() {
        // render
        that.render();
      }
    );

  },

  resourceClick: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'click');
    }
  },

  resourceTouch: function( element ) {
    var that = this;

    if(that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mouseenter');
    }
  },

  resourceHover: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mouseenter');
    }
  },

  resourceOut: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mouseleave');
    }

  },
  resourceEvent: function ( element, eventType ){
    var that = this;
    switch (eventType) {
      case 'click':
          that.parentExplorer.triggerEvent('resourceClick',{id:$(element.currentTarget).attr('data-resource-id')} );

          // call metrics event
          that.parentExplorer.metricsResourceController.eventClick( id, 'Explorer: '+that.parentExplorer.options.explorerSectionName );
      break;
      case 'mouseenter':
        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.displays.map.markerBounce( $(element.currentTarget).attr('data-resource-id') );
          that.parentExplorer.displays.map.markerHover( $(element.currentTarget).attr('data-resource-id') );
          that.parentExplorer.displays.map.panTo( $(element.currentTarget).attr('data-resource-id') );
        }
        else {
          that.parentExplorer.metricsResourceController.eventHoverStart(
            $(element.currentTarget).attr('data-resource-id') ,
            'Explorer: '+that.parentExplorer.options.explorerSectionName
          );
        }
      break;
      case 'mouseleave':
        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.displays.map.markerOut( );
          that.parentExplorer.displays.map.markerBounceEnd( $(element.currentTarget).attr('data-resource-id') );
        }

        that.parentExplorer.metricsResourceController.eventHoverEnd(
          $(element.currentTarget).attr('data-resource-id')
        );

      break;
    }
  }
});
