$(document).ready( function(){



  var historia  = new geozzy.story({
    storyReference:'castro'
  });


  var displayLista = new geozzy.storyComponents.StoryListView({
    container: '.storyBody .lista'
  });


  historia.addDisplay( displayLista );


  historia.exec();
});
