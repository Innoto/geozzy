var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.listElementTemplate = ''+
    '<div class="storyStep" >' +
      '<%if(img){%>'+
        '<div><img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/fast_cut/<%-img%>.jpg" /></div>' +
      '<%}%>'+
      '<h3 class="title"><%= title %></h3>'+
      '<div class="mediumDescription"><%= mediumDescription %></div>'+
    '</div>';
