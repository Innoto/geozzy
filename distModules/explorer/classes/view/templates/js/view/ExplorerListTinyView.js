var geozzy = geozzy || {};
geozzy.explorerComponents = geozzy.explorerComponents || {}; //if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.listTinyView = Backbone.View.extend({

  tpl: false,
  tplElement: false,
  tplPager: false,

  currentPage: 0,

  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],

  events: {
    "click  .next" : "nextPage",
    "click  .previous" : "previousPage",
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
      endPage: false,
      itemsEachPage: 6,
      totalPages: false,

      categories: false,
      //sortByResourceWeight:false,

      tpl: geozzy.explorerComponents.activeListTinyViewTemplate ,
      tplElement: geozzy.explorerComponents.activeListTinyViewElement,
      tplPager: geozzy.explorerComponents.activeListTinyViewPager,
      tplEmpty: geozzy.explorerComponents.listEmpty

    });
    that.options = $.extend(true, {}, options, opts);

    that.tpl= _.template( that.options.tpl);
    that.tplElement= _.template( that.options.tplElement);
    that.tplPager= _.template( that.options.tplPager);
    that.tplEmpty = _.template(geozzy.explorerComponents.listEmpty);
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
  },

  getVisibleResourceIds: function() {
    var that = this;
    var ret = false;

    if(typeof that.parentExplorer.resourceIndex.removePagination != 'undefined'){
      that.parentExplorer.resourceIndex.removePagination();

      var visibleResources = that.parentExplorer.resourceIndex.setPerPage(that.options.itemsEachPage);

      //if( that.options.sortByResourceWeight == true ) {
        //visibleResources.setSort('mapVisible', 'desc');
        //visibleResources.setSort('weight', 'asc');
      //}
      //else {
        //visibleResources.setSort('distanceToCenterKm', 'asc');
      //}


      // ordenado múltiple
      visibleResources.setSort( function(model) {
        var mapVisible = model.get('mapVisible'); // DESC
        var peso = 10000 - model.get('weight'); //ASC
        var dist = parseInt( Math.round( (1000000 * 100) - (model.get('distanceToCenterKm') * 100) ) ); // DESC
        var ret = mapVisible.toString() + peso.toString() + dist.toString();
        return parseInt(ret);
      }, 'desc');


      // get total packages
      that.options.totalPages = that.parentExplorer.resourceIndex.getNumPages();

      // set current page
      visibleResources.setPage(that.currentPage);

      that.visibleResources = visibleResources.pluck( 'id' );
      ret = visibleResources.pluck( 'id' );
    }

    return ret;
  },

  getMapVisibleResourceIds: function() {
    var that = this;
    var ret = false;

    if(typeof that.parentExplorer.resourceIndex.removePagination != 'undefined'){
      that.parentExplorer.resourceIndex.removePagination();

      var visibleResources = that.parentExplorer.resourceIndex.setSort('mapVisible', 'desc');

      ret = visibleResources.pluck( 'id' );
    }

    return ret;
  },


  render: function() {
    var that = this;


    that.$el.html('');
    var contador = 1;



    var contentHtml = '';
    if( this.visibleResources) {

      $.each(  this.visibleResources, function(i,e){


        var elementCategory = false;
        if( that.options.categories ) {
          that.options.categories.each( function(e2){
            //cogumelo.log(e.get('id'));
            //console.debug(markerData.get('terms'));

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

        element.inMap = that.parentExplorer.resourceMinimalList.get( e ).get('mapVisible');

        that.parentExplorer.triggerEvent('resourcePrint',{
          id: that.parentExplorer.resourcePartialList.get( e ).get('id'),
          section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
        });

        contentHtml += that.tplElement(element);
        contador++;
      });



      that.$el.html( that.tpl({ pager:  this.renderPager() , content: contentHtml }) );
    }

    that.parentExplorer.triggerEvent('onRenderListComplete',{});

    if( typeof geozzy.rExtFavouriteController !='undefined'  && typeof geozzy.rExtFavouriteController.setBinds === 'function' ) {
      $( '.rExtFavouriteHidden' ).css( 'display', 'inline-block' ).removeClass( 'rExtFavouriteHidden' );
      geozzy.rExtFavouriteController.setBindsAndGetStatus();
    }
  },

  renderPager: function renderPager() {
    var that = this;

    var pages = Math.ceil(that.getMapVisibleResourceIds().length/that.options.itemsEachPage );

    if( that.options.endPage < pages  &&  that.options.endPage != false) {
      pages = that.options.endPage;
    }

    return that.tplPager({ v:that, pages:pages-1 } );
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

  setPageClick: function( elemento ) {
    var that = this;

    that.setPage( $(elemento.target).attr('data-page-num')  );
  },

  nextPage: function() {
    var that =  this;
    var endPage = that.options.endPage;
    var pages = Math.ceil(that.getMapVisibleResourceIds().length/that.options.itemsEachPage );

    if(endPage == false) {
      endPage = pages;
    }

    var nextPage = that.currentPage+1;

    if( nextPage > endPage-1 ) {
      nextPage = endPage-1;
    }
    else
    if( nextPage > pages-1  ) {
      nextPage = pages-1;
    }


    //cogumelo.log(that.getMapVisibleResourceIds().length,that.options.itemsEachPage, Math.ceil(that.getMapVisibleResourceIds().length/that.options.itemsEachPage ));

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

        that.parentExplorer.triggerEvent('resourceClick',{
          id: $(element.currentTarget).attr('data-resource-id'),
          section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
        });
        break;
      case 'mouseenter':
        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.displays.map.markerBounce( $(element.currentTarget).attr('data-resource-id') );
          //that.parentExplorer.displays.map.markerHover( $(element.currentTarget).attr('data-resource-id') );
          that.parentExplorer.displays.map.panTo( $(element.currentTarget).attr('data-resource-id') );
        }

        that.parentExplorer.triggerEvent('resourceHover', {
          id: $(element.currentTarget).attr('data-resource-id'),
          section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
        });
        break;
      case 'mouseleave':

        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.displays.map.markerOut( );
          that.parentExplorer.displays.map.markerBounceEnd( $(element.currentTarget).attr('data-resource-id') );

        }
        that.parentExplorer.triggerEvent('resourceMouseOut', { id: $(element.currentTarget).attr('data-resource-id') });

        break;
    }
  }
});
