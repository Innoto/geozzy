var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};




geozzy.biMetricsComponents.biConfiguration = function() {

  var that = this;

  that.conf = false;
  that.confAjaxCall = $.ajax({
      dataType: "json",
      url:'/api/core/bi',
      cache: true,
      success: function( res ){
        that.conf = res;
        that.confAjaxCall =  false;
      }
  });

  that.getConf = function( getConfCallback ) {

    if( that.confAjaxCall !== false ) {
      $.when( that.confAjaxCall ).then( getConfCallback );
    }
    else  {
      getConfCallback();
    }
  };

};
