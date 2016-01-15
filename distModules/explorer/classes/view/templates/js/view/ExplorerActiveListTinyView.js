var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.activeListTinyView = Backbone.View.extend({

  tpl: _.template(
    '<div class="explorerListPager">'+
      '<%=pager%>'+
    '</div>'+
    '<div class="explorerListContent container-fluid">'+
      '<div class="">'+
        '<%=content%>'+
      '</div>'+
    '</div>'),
  tplElement: _.template(
    '<div data-resource-id="<%- id %>" class="accessButton col-md-2 col-sm-2 col-xs-4 element element-<%- id %>">'+
      '<div class="elementImg">'+
        '<img class="img-responsive" src="/cgmlImg/<%- img %>/fast_cut/.jpg" />'+
        '<ul class="elementOptions container-fluid">'+
          '<li class="elementOpt elementFav"><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i></li>'+
        '</ul>'+
      '</div>'+
      '<div class="elementInfo">'+
        '<%-title%>'+
      '</div>'+
    '</div>'),
  tplPager: _.template(
    '<div class="previous"><i class="fa fa-sort-asc"></i></div>'+
    '<% for( c=0 ; c <= pages ; c++){ %>'+
      '<% if(c==v.currentPage){ %>'+
        '<div><span class="currentPage"><i class="fa fa-square-o"></i></span></div>'+
      '<% }else{ %>'+
        '<div><span><i class="fa fa-square"></i></span></div>'+
      '<% } %>'+
    '<% } %>'+
    '<div class="next"><i class="fa fa-sort-desc"></i></div>'),

  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,
  endPage: 3,
  totalPages: false,


  events: {
      "click .explorerListPager .next" : "nextPage",
      "click .explorerListPager .previous" : "previousPage",

      // resource events
      "click .explorerListContent .accessButton": "resourceClick",
      "mouseenter .explorerListContent .element": "resourceHover",
      "mouseleave .explorerListContent": "resourceOut",
  },

  initialize: function( opts ) {
    var that = this;
    that.options = new Object({
      showInBuffer: true,
      showOutMapAndBuffer: false
    });
    $.extend(true, that.options, opts);

  },


  getVisibleResourceIds: function() {
    var that = this;
    this.parentExplorer.resourceIndex.removePagination();

    var visibleResources = that.parentExplorer.resourceIndex.setPerPage(6);
/*
    that.parentExplorer.resourceIndex.filterBy( function(e) {
      var ret = false;
      var mapVisible = e.get('mapVisible');

      if( that.options.showOutMapAndBuffer == true && ( mapVisible == 0) ) {
        ret = true;
      }
      else if( that.options.showInBuffer == true && mapVisible==1) {
        ret = true;
      }
      else if( mapVisible == 2 || mapVisible==3) {
        ret = true;
      }


      //console.log(mapVisible, ret)

      return ret;
    });*/

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


    that.$el.html( that.tpl({ pager:  this.renderPager() , content: contentHtml }) )

  },

  renderPager() {
    var that = this;

    var pages = that.endPage;

    if( that.limitPages < that.totalPages && that.endPage) {
      pages = that.endPage;
    }


    return this.tplPager({ v:that, pages:pages } );
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


  nextPage: function() {
    var that =  this;
    that.setPage(that.currentPage+1);
  },

  previousPage: function( ){
    var that = this;
    that.setPage(that.currentPage-1);
  },

  resourceClick: function( element ) {
    var that = this;
    if( that.parentExplorer.displays.map ) {

      that.parentExplorer.explorerRouter.navigate( 'resource/' + $(element.currentTarget).attr('data-resource-id'), {trigger:true} );
    }
    else {
      that.parentExplorer.options.resourceAccess( id, {trigger:true} )
      // call metrics event
      that.parentExplorer.metricsResourceController.eventClick( id, 'Explorer: '+that.parentExplorer.options.explorerSectionName );
    }

  },

  resourceHover: function( element ) {
    var that = this;

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
  },

  resourceOut: function( element ) {
    var that = this;

    if( that.parentExplorer.displays.map ) {
      that.parentExplorer.displays.map.markerOut( );
    }
    else {
      that.parentExplorer.metricsResourceController.eventHoverEnd(
        $(element.currentTarget).attr('data-resource-id')
      );
    }


  }
});
