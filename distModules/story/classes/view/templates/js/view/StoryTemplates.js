var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.listElementTemplate = ''+
    '<div class="storyStep" >' +
      '<%if( img && dialogPosition == 0 ){%>'+
        '<div><img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/storyCentered/<%-img%>.jpg" /></div>' +
      '<%}else {%>'+
        '<div><img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/fast_cut/<%-img%>.jpg" /></div>' +
      '<%}%>'+
      '<div class="content">' +
        '<h3 class="title"><%= title %></h3>'+
        '<div class="mediumDescription"><%= mediumDescription %></div>'+
        '<div class="resourceButton">' +
          '<% if( relatedResource ) { %> <a class="resourceAccessButton" dataResourceAccessButton=<%-relatedResource%> ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>' +
        '</div>' +
      '</div>' +
    '</div>';

geozzy.storyComponents.InfowindowPOI = ''+
    '<div class="poiInfoWindow">'+
      '<div class="poiImg">'+
        '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>/squareCut/<%-image%>.jpg" />'+
        '<% if( isNormalResource == 1 ) { %> <a target="_blank" href="/resource/<%-id%>" ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>'+
      '</div>'+
      '<div class="poiInfo">'+
        '<div class="poiTitle"><p><%-title%></p></div>'+
        '<div class="poiDescription"><%=description%></div>'+
      '</div>'+
    '</div>';
