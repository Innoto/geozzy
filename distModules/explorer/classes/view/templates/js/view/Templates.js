var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};


geozzy.explorerDisplay.mapInfoViewTemplate = '<div class="gempiContent">'+
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
