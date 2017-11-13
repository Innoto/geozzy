var geozzy = geozzy || {};

$(document).ready( function() {

  if( typeof geozzy.rExtMapInstanceManager == 'undefined' ) {

    geozzy.rExtMapInstanceManager = {
      previousInstances: [],
      waitNewInstance: function() {
        this.previousInstances.push( geozzy.rExtMapInstance );
        geozzy.rExtMapInstance = false;
      },
      back: function() {
        var instance = this.previousInstances.pop();
        if(typeof instance != 'undefined') {
          geozzy.rExtMapInstance = instance;
        }

      }
    };
  }

});
