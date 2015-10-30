var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.pasiveListView = Backbone.View.extend({

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
    '<div resourceId="<%- id %>" class="col-md-2 col-sm-4 col-xs-6 element element-<%- id %>">'+
      '<div class="elementImg">'+
        '<img class="img-responsive" src="http://lorempixel.com/260/196/nature?<%- id %>" />'+
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

  displayType: 'pasiveList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,
  endPage: 3,
  totalPages: false,


  events: {
      "click .explorerListPager .next" : "nextPage",
      "click .explorerListPager .previous" : "previousPage",
      "mouseenter .explorerListContent .element": "resourceHover",
      "mouseleave .explorerListContent": "resourceOut",
  },

  initialize: function( opts ) {

  },


  getVisibleResourceIds: function() {
    this.parentExplorer.resourceIndex.removePagination();

    var visibleResources = this.parentExplorer.resourceIndex.setPerPage(6);

    visibleResources.setSort('mapVisible', 'desc');

    // get total packages
    this.totalPages = this.parentExplorer.resourceIndex.getNumPages();

    // set current page
    visibleResources.setPage(this.currentPage);

    this.visibleResources = visibleResources.pluck( 'id' );
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
        inMap: that.parentExplorer.resourceMinimalList.get( e ).get('mapVisible')
      };

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
    this.setPage(this.currentPage+1);
  },

  previousPage: function( ){
    this.setPage(this.currentPage-1);
  },

  resourceHover: function( element ) {
    var that = this;

    if( that.parentExplorer.displays.map ) {
      that.parentExplorer.displays.map.markerBounce( $(element.currentTarget).attr('resourceId') );
      that.parentExplorer.displays.map.markerHover( $(element.currentTarget).attr('resourceId') );
    }
  },

  resourceOut: function( element ) {
    var that = this;

    if( that.parentExplorer.displays.map ) {
      that.parentExplorer.displays.map.markerOut( );
    }


  }
});
