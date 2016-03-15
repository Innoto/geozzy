var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};


geozzy.explorerDisplay.mapInfoViewTemplate = ''+
  '<div class="gempiContent">'+
    '<div class="gempiImg">'+
      '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/fast_cut/<%-img%>.jpg" />'+
      '<div class="gempiFav"><% if(touchAccess){ %><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i> <% } %></div>'+
    '</div>'+
    '<div class="gempiInfo">'+
      '<div class="gempiTitle"><%-title%></div>'+
      '<div class="gempiLocation"><% if(city){ %><%- city %> <% } %></div>'+
      '<div class="gempiDescription"><%-description%></div>'+
      '<div class="gempiTouchAccess"><% if(touchAccess){ %><button class="btn btn-primary accessButton">Descúbreo</button> <% } %></div>'+
    '</div>'+
  '</div>';


geozzy.explorerDisplay.activeListTinyViewTemplate = ''+
  '<div class="explorerListPager">'+
    '<%=pager%>'+
  '</div>'+
  '<div class="explorerListContent container-fluid">'+
    '<div class="">'+
      '<%=content%>'+
    '</div>'+
  '</div>';

geozzy.explorerDisplay.activeListTinyViewElement = ''+
  '<div data-resource-id="<%- id %>" class="col-md-2 col-sm-2 col-xs-4 element element-<%- id %>">'+
    '<div class="elementImg">'+
      '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/fast_cut/<%- img %>.jpg" />'+
      '<div data-resource-id="<%- id %>" class="elementHover accessButton">'+
        '<ul class="elementOptions container-fluid">'+
          '<li class="elementOpt elementFav"><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i></li>'+
        '</ul>'+
      '</div>'+
    '</div>'+

    '<div class="elementInfo">'+
      '<%-title%>'+
    '</div>'+
  '</div>';

geozzy.explorerDisplay.activeListTinyViewPager = ''+
  '<div class="previous"><i class="fa fa-sort-asc"></i></div>'+
    '<% for( c=0 ; c <= pages ; c++){ %>'+
      '<% if(c==v.currentPage){ %>'+
        '<div><span class="currentPage"><i class="fa fa-square-o"></i></span></div>'+
      '<% }else{ %>'+
        '<div><span><i class="fa fa-square pageNum" data-page-num="<%- c %>"></i></span></div>'+
      '<% } %>'+
    '<% } %>'+
  '<div class="next"><i class="fa fa-sort-desc"></i></div>';

geozzy.explorerDisplay.activeListViewTemplate = ''+
  '<div class="explorerActiveListContent">'+
      '<%=content%>'+
  '</div>';

geozzy.explorerDisplay.activeListViewElement = '' +
  '<div data-resource-id="<%- id %>" class="col-md-12 element">'+
    '<div class="elementImg">'+
      '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/explorerXantaresImg/<%- img %>.jpg" />'+
      '<div data-resource-id="<%- id %>" class="elementHover accessButton">'+
        '<ul class="elementOptions container-fluid">'+
          '<li class="elementOpt elementFav"><i class="fa fa-heart-o"></i><i class="fa fa-heart"></i></li>'+
        '</ul>'+
      '</div>'+
    '</div>'+
    '<div class="elementInfo">'+
      '<div class="elementTitle"><%-title%></div>'+
      '<div class="elementType"><img src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- category.icon %>/typeIconMini/<%- category.icon %>.png"/></i> <%- category.name %></div>'+
      '<% if( typeof averagePrice != "undefined" ){%> <div class="elementPrice"> <%= averagePrice %>€<span>/persona</span> </div> <%}%>'+
    '</div>'+
  '</div>';
