var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.pasiveListView = Backbone.View.extend({

  parentExplorer: false,

  getVisibleResourceIds: function() {

    var visibleResources = this.parentExplorer.resourceCurrentIndex.setPerPage(100);
    return visibleResources.pluck( 'id' );
  },


  render: function() {
    var that = this;
    $.each(  that.parentExplorer.resourceCurrentIndex.pluck('id'), function(i,e){
      $('#explorerList').append('<div> - '+  that.parentExplorer.resourcePartialList.get( e ).get('title') +'</div><br>');
    });

  }

});
