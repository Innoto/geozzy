var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.listElementTemplate = ''+
    '<div class="storyStep" style="width:400px;margin-bottom :400px; ">' +
      '<%if(img){%>'+
        '<div><img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/fast_cut/<%-img%>.jpg" /></div>' +
      '<%}%>'+
      '<h3><%= title %></h3>'+
      '<div class=""><%= mediumDescription %></div>'+
    '</div>';
