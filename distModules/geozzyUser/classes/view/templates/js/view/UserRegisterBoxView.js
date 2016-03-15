var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.activeListView = Backbone.View.extend({

  tpl: _.template(
    '<div class="explorerActiveListContent">'+
        '<%=content%>'+
    '</div>'),
  tplElement: _.template(
    /*
    '<div data-resource-id="<%- id %>" class="accessButton col-md-2 col-sm-2 col-xs-4 element element-<%- id %>">'+
      '<div class="elementImg">'+
        '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/fast_cut/.jpg" />'+
        '<ul class="elementOptions container-fluid">'+
          '<li class="elementOpt elementFav"><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i></li>'+
        '</ul>'+
      '</div>'+
      '<div class="elementInfo">'+
        '<%-title%>'+
      '</div>'+
    '</div>'),*/
    '<div data-resource-id="<%- id %>" class="col-md-12 element">'+
      '<div class="elementImg">'+
        '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/explorerXantaresImg/<%- img %>.jpg" />'+
        '<div data-resource-id="<%- id %>" class="elementHover accessButton">'+
          '<ul class="elementOptions container-fluid">'+
            '<li class="elementOpt elementFav"><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i></li>'+
          '</ul>'+
        '</div>'+
      '</div>'+
      '<div class="elementInfo">'+
        '<div class="elementTitle"><%-title%></div>'+
        '<div class="elementType"><img src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- category.icon %>/typeIconMini/<%- category.icon %>.png"/></i> <%- category.name %></div>'+
        '<% if( averagePrice ){%> <div class="elementPrice"> <%= averagePrice %>€<span>/persona</span> </div> <%}%>'+
      '</div>'+
    '</div>'),



  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,
  endPage: 3,
  totalPages: false,


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
      cateogories: false
    });

    that.options = $.extend(true, {}, options, opts);

  },


  getVisibleResourceIds: function() {
    var that = this;
    this.parentExplorer.resourceIndex.removePagination();

    var visibleResources = that.parentExplorer.resourceIndex.setPerPage(30);


    visibleResources.setSort('mapVisible', 'desc');

    // get total packages
    that.totalPages = that.parentExplorer.resourceIndex.getNumPages();

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




      var element = {
        contador: contador,
        title: that.parentExplorer.resourcePartialList.get( e ).get('title'),
        id: that.parentExplorer.resourcePartialList.get( e ).get('id'),
        inMap: that.parentExplorer.resourceMinimalList.get( e ).get('mapVisible'),
        img: that.parentExplorer.resourceMinimalList.get( e ).get('img'),
        averagePrice: that.parentExplorer.resourceMinimalList.get( e ).get('averagePrice'),
        category: elementCategory

      };


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

    if( that.endPage != false && pageNum > that.endPage ) {

      pageNum = that.endPage;

    }
    else
    if ( that.endPage != false && pageNum > that.totalPages ){
      pageNum = that.totalPages;
    }
    else
    if( that.endPage == false &&  pageNum > that.totalPages  ){
      pageNum = that.endPage
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
        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.explorerRouter.navigate( 'resource/' + $(element.currentTarget).attr('data-resource-id'), {trigger:true} );
        }
        else {
          that.parentExplorer.options.resourceAccess( id, {trigger:true} )
          // call metrics event
          that.parentExplorer.metricsResourceController.eventClick( id, 'Explorer: '+that.parentExplorer.options.explorerSectionName );
        }
      break;
      case 'mouseenter':
        if( that.parentExplorer.displays.map ) {
          that.parentExplorer.displays.map.panTo( $(element.currentTarget).attr('data-resource-id') );
          that.parentExplorer.displays.map.markerBounce( $(element.currentTarget).attr('data-resource-id') );
          that.parentExplorer.displays.map.markerHover( $(element.currentTarget).attr('data-resource-id') );
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
