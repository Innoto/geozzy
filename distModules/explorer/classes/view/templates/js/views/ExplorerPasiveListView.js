var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.pasiveListView = Backbone.View.extend({

  tpl: _.template(
    '<div class="explorerListPager">'+
      //'<%=pager%>'+
    '</div>'+
    '<div class="explorerListContent container-fluid">'+
      '<div class="">'+
        '<%=content%>'+
      '</div>'+
    '</div>'),
  tplElement: _.template(
    '<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 element element-<%- id %>">'+
      '<div class="elementImg">'+
        '<img class="img-responsive" src="http://lorempixel.com/400/250/nature" />'+
      '</div>'+
      '<div class="elementInfo">'+
        '<%-title%>'+
      '</div>'+
    '</div>'),
  tplPager: _.template(' <span class="previous">◀</span> <% for( c=0 ; c <= pages ; c++){ %> <span class="page" <% if(c==v.currentPage){ %>style="color:white;"<% } %>>●</span> <%} %> <span class="next">▶</span>'),

  displayType: 'pasiveList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,
  endPage: 3,
  totalPages: false,


  events: {
      "click .pager .next" : "nextPage",
      "click .pager .previous" : "previousPage"
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
  }

});
