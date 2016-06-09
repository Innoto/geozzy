var geozzy = geozzy || {};
geozzy.explorerComponents = geozzy.explorerComponents || {}; //if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.activeListTinyView = Backbone.View.extend({

  tpl: false,
  tplElement: false,
  tplPager: false,

  currentPage: 0,

  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],

  events: {
    "click .explorerListPager .next" : "nextPage",
    "click .explorerListPager .previous" : "previousPage",
    "click .explorerListPager .pageNum" : "setPageClick",
    // resource events
    "click .explorerListContent .accessButton": "resourceClick",
    "touchend .explorerListContent .element": "resourceTouch",
    "mouseenter .explorerListContent .element": "resourceHover",
    "mouseleave .explorerListContent .element": "resourceOut",
  },

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      showInBuffer: true,
      showOutMapAndBuffer: false,

      currentPage: 0,
      endPage: 3,
      itemsEachPage: 6,
      totalPages: false,

      tpl: geozzy.explorerComponents.activeListTinyViewTemplate ,
      tplElement: geozzy.explorerComponents.activeListTinyViewElement,
      tplPager: geozzy.explorerComponents.activeListTinyViewPager

    });
    that.options = $.extend(true, {}, options, opts);

    that.tpl= _.template( that.options.tpl);
    that.tplElement= _.template( that.options.tplElement);
    that.tplPager= _.template( that.options.tplPager);
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },

  getVisibleResourceIds: function() {
    var that = this;
    if(typeof that.parentExplorer.resourceIndex.removePagination != 'undefined'){
      that.parentExplorer.resourceIndex.removePagination();
    }

    var visibleResources = that.parentExplorer.resourceIndex.setPerPage(that.options.itemsEachPage);

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

      var element = {
        contador: contador,
        title: that.parentExplorer.resourcePartialList.get( e ).get('title'),
        id: that.parentExplorer.resourcePartialList.get( e ).get('id'),
        inMap: that.parentExplorer.resourceMinimalList.get( e ).get('mapVisible'),
        img: that.parentExplorer.resourceMinimalList.get( e ).get('img')
      };


      // metrics
      that.parentExplorer.metricsResourceController.eventPrint(
        that.parentExplorer.resourcePartialList.get( e ).get('id'),
        'Explorer: '+that.parentExplorer.options.explorerSectionName
      );

      contentHtml += that.tplElement(element);
      contador++;
    });

    that.$el.html( that.tpl({ pager:  this.renderPager() , content: contentHtml }) );

    that.onRenderComplete();
  },

  onRenderComplete: function onRenderComplete() {
    //console.log( 'geozzy.explorerComponents.activeListTinyView.onRenderComplete()' );
    if( typeof geozzy.rExtFavouriteController.setBinds === 'function' ) {
      $( '.rExtFavouriteHidden' ).css( 'display', 'inline-block' ).removeClass( 'rExtFavouriteHidden' );
      geozzy.rExtFavouriteController.setBindsAndGetStatus();
    }
  },

  renderPager: function renderPager() {
    var that = this;

    var pages = Math.ceil(that.parentExplorer.resourceMinimalList.length/that.options.itemsEachPage );

    if( that.options.endPage < pages ) {
      pages = that.options.endPage;
    }

    return this.tplPager({ v:that, pages:pages-1 } );
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

  setPageClick: function( elemento ) {
    var that = this;

    that.setPage( $(elemento.target).attr('data-page-num')  );
  },

  nextPage: function() {
    var that =  this;

    var pages = Math.ceil(that.parentExplorer.resourceMinimalList.length/that.options.itemsEachPage );
    var nextPage = that.currentPage+1;

    if( nextPage > that.options.endPage-1 ) {
      nextPage = that.options.endPage-1;
    }
    else
    if( nextPage > pages-1  ) {
      nextPage = pages-1;
    }

    that.setPage(nextPage);
  },

  previousPage: function( ){
    var that = this;
    that.setPage(that.currentPage-1);
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
        that.parentExplorer.metricsResourceController.eventClick( $(element.currentTarget).attr('data-resource-id'), 'Explorer: '+that.parentExplorer.options.explorerSectionName );
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
