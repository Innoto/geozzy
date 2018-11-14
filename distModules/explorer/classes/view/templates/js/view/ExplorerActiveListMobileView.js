var geozzy = geozzy || {};
geozzy.explorerComponents = geozzy.explorerComponents || {}; //if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.activeListMobileView = Backbone.View.extend({

  tpl: false,
  tplElement: false,

  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],

  listOrderAccordingIds: true,

  currentPage: 0,

  events: {
    // resource events
    "click .explorerActiveListContent .element": "resourceTouch",
    "click .activeListReset": "setListOrderAccordingIds"
    //"touchend .explorerActiveListContent .element": "resourceTouch",
    //"mouseenter .explorerActiveListContent .element": "resourceHover",
    //"mouseleave .explorerActiveListContent .element": "resourceOut",
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
      tplElement: geozzy.explorerComponents.activeListViewElement,
      tplEmpty: geozzy.explorerComponents.listEmpty
    });

    that.options = $.extend(true, {}, options, opts);

    that.tpl = _.template(that.options.tpl);
    that.tplElement = _.template(that.options.tplElement);
    that.tplEmpty = _.template(that.options.tplEmpty);

  },


  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('mapChanged', function(){
      that.setListOrderAccordingMap();
    });
  },


  setListOrderAccordingMap: function() {
    var that = this;

    that.listOrderAccordingIds = false;
    //that.$el.find('.activeListReset').show();
    that.render();
  },

  setListOrderAccordingIds: function() {
    var that = this;

    that.listOrderAccordingIds = true;
    //that.$el.find('.activeListReset').hide();ç
    that.setPage(0);
    that.render();

  },

  getVisibleResourceIds: function() {
    var that = this;
    var ret = false;
    if(typeof that.parentExplorer.resourceIndex.removePagination != 'undefined'){
      that.parentExplorer.resourceIndex.removePagination();


      var visibleResources = that.parentExplorer.resourceIndex.setPerPage(100);


      if( that.listOrderAccordingIds == true ) {
        visibleResources.setSort('weight','asc');
        visibleResources.setSort('id','asc');
      }
      else {
        visibleResources.setSort('distanceToCenterKm', 'asc');
      }


      // get total packages
      that.options.totalPages = that.parentExplorer.resourceIndex.getNumPages();

      // set current page
      visibleResources.setPage(that.currentPage);

      that.visibleResources = visibleResources.pluck( 'id' );

      ret = visibleResources.pluck( 'id' );
    }


    return ret;

  },


  render: function() {
    var that = this;

    that.$el.html('');
    var contador = 1;

    var contentHtml = '';
    $.each(  this.visibleResources, function(i,e){

      //that.options.categories
      //console.log( that.parentExplorer.resourceMinimalList.toJSON/ )

      var elementCategory = false;
      if( that.options.categories ) {
        that.options.categories.each( function(e2){
          //console.log(e.get('id'))
          //console.debug(markerData.get('terms'))

          if( $.inArray(e2.get('id'), that.parentExplorer.resourceMinimalList.get( e ).get('terms')  ) > -1 ) {

            elementCategory = e2;
            if(e2) {
              elementCategory = e2.toJSON();
            }
            return false;
            /*
            if( jQuery.isNumeric( e2.get('icon') )  ){
              return false;
            }*/
          }
        });
      }


      var minJSON = that.parentExplorer.resourceMinimalList.get( e ).toJSON();
      var partJSON = that.parentExplorer.resourcePartialList.get( e ).toJSON();

      var element = $.extend( true, partJSON, minJSON );

      element.contador = contador;
      element.category = elementCategory;

      // metrics
      /*that.parentExplorer.metricsResourceController.eventPrint(
        that.parentExplorer.resourcePartialList.get( e ).get('id'),
        'Explorer: '+that.parentExplorer.options.explorerSectionName
      );*/
      that.parentExplorer.triggerEvent('resourcePrint',{
        id: that.parentExplorer.resourcePartialList.get( e ).get('id'),
        section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
      });

      contentHtml += that.tplElement(element);
      contador++;
    });

    var showActiveListReset;

    if(that.listOrderAccordingIds) {
      showActiveListReset = false;
    }
    else {
      showActiveListReset = true;
    }

    that.$el.html( that.tpl({
      activeListReset: showActiveListReset,
      activeListResetText:__('Prioritizing map zone'),
      content: contentHtml
    }) );

    that.parentExplorer.triggerEvent('onRenderListComplete',{});

    if( typeof geozzy.rExtFavouriteController !='undefined'  && typeof geozzy.rExtFavouriteController.setBinds === 'function' ) {
      $( '.rExtFavouriteHidden' ).css( 'display', 'inline-block' ).removeClass( 'rExtFavouriteHidden' );
      geozzy.rExtFavouriteController.setBindsAndGetStatus();
    }
  },
/*
  onRenderComplete: function onRenderComplete() {
    //console.log( 'geozzy.explorerComponents.activeListView.onRenderComplete()' );
    if( typeof geozzy.rExtFavouriteController !='undefined'  && typeof geozzy.rExtFavouriteController.setBinds === 'function' ) {
      $( '.rExtFavouriteHidden' ).css( 'display', 'inline-block' ).removeClass( 'rExtFavouriteHidden' );
      geozzy.rExtFavouriteController.setBindsAndGetStatus();
    }
  },*/

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
      pageNum = that.options.endPage;
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
    if(that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'resourceClick');
    }
  },

  resourceTouch: function( element ) {
    var that = this;

    if(that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mobileTouch');
    }
  },

  resourceHover: function( element ) {
    var that = this;
    if(!that.parentExplorer.explorerTouchDevice){
      that.resourceEvent( element, 'mobileTouch');
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
          that.parentExplorer.triggerEvent('resourceClick',{
            id: $(element.currentTarget).attr('data-resource-id'),
            section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
          });
          break;
      case 'mobileTouch':
        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.displays.map.panTo( $(element.currentTarget).attr('data-resource-id') );
        }

        that.parentExplorer.triggerEvent('mobileTouch', {
          id: $(element.currentTarget).attr('data-resource-id'),
          section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
        });
        that.parentExplorer.triggerEvent('resourceHover',{
          id: $(element.currentTarget).attr('data-resource-id'),
          section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
        });
        break;
      case 'mouseleave':
        that.parentExplorer.triggerEvent('resourceMouseOut', {id:$(element.currentTarget).attr('data-resource-id')});
        break;
    }
  }
});
