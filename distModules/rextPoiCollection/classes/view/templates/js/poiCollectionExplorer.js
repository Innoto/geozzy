
  $(document).ready(function(){

    /* EXPLORER MAIN CLASS DECLARATION */
    var ex = new geozzy.explorer({
      explorerId:'paisaxes',
      explorerSectionName:'Puntos de interese',
      debug:false
    });
    /* EXPLORER DISPLAY DECLARATION  (SET CUSTOM ICON TOO)  */
    var explorerMapa = new geozzy.explorerDisplay.mapView({
        map: geozzy.rExtMapInstance.resourceMap,
        clusterize:false
    });
    /* ADD DISPLAY TO EXPLORER */
    ex.addDisplay( explorerMapa );
    /* EXEC EXPLORER */
    ex.exec();
  });
