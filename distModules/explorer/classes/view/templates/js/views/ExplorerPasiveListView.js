var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.pasiveListView = Backbone.View.extend({

  displayType: 'pasiveList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,
  endPage: false,
  totalPages: false,

  initialize: function( opts ) {

  },

  getVisibleResourceIds: function() {
    this.parentExplorer.resourceCurrentIndex.removePagination();


    var visibleResources = this.parentExplorer.resourceCurrentIndex.setPerPage(4);


    // get total packages
    this.totalPages = this.parentExplorer.resourceCurrentIndex.getNumPages();

    // set current page
    visibleResources.setPage(this.currentPage);

    this.visibleResources = visibleResources.pluck( 'id' );

    return this.visibleResources;
  },


  render: function() {
    var that = this;
    $('.explorer-container-gallery').html('');
    var contador = 1;


    $('.explorer-container-gallery').html( this.renderPager() );

    $.each(  that.parentExplorer.resourceCurrentIndex.pluck('id'), function(i,e){
      $('.explorer-container-gallery').append('<div> ' + contador + '- '+  that.parentExplorer.resourcePartialList.get( e ).get('title') +' '+that.parentExplorer.resourcePartialList.get( e ).get('id') +'</div><br>');
      contador++;
    });

  },

  renderPager() {
    return ' ◀••••▶';
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

  }


});
