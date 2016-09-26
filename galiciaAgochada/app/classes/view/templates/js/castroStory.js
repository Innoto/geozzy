$(document).ready( function(){



  var historia  = new geozzy.story({
    storyReference:'castro'
  });


  displayLista = new geozzy.storyComponents.StoryListView({
    container: '.storyBody .lista'
  });


  historia.addDisplay( displayLista );


  historia.exec();
});
