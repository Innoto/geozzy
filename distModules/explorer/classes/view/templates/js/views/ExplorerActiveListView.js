var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.activeListView = Backbone.View.extend({

  displayType: 'activeList',
  parentExplorer: false,
  visibleResources: [],
  currentPage: 0,

  getVisibleResourceIds: function() {
    this.parentExplorer.resourceCurrentIndex.removePagination();
    var visibleResources = this.parentExplorer.resourceCurrentIndex.setPerPage(1);

    visibleResources.lastPage();

    this.visibleResources = visibleResources.pluck( 'id' );

    return this.visibleResources;
  },


  render: function() {
    var that = this;
    $('.explorer-container-gallery').html('');
    var contador = 1;
    $.each(  that.parentExplorer.resourceCurrentIndex.pluck('id'), function(i,e){
      $('.explorer-container-gallery').append('<div> ' + contador + '- '+  that.parentExplorer.resourcePartialList.get( e ).get('title') +'</div><br>');
      contador++;
    });

  }

});
