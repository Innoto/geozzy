var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};


geozzy.explorerComponents.mapInfoViewTemplate = ''+
  '<% if('+cogumelo.publicConf.mod_detectMobile_isTablet+'){ %> <button class="buttonTabletClose"><i class="fas fa-times"></i><button> <% } %>' +
  '<div class="gempiContent">'+
    '<div class="gempiImg">'+
      '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/wmdpi4/<%-img%>.jpg">'+
    '</div>'+
    '<div class="gempiInfo">'+
      '<div class="gempiTitle"><%-title%></div>'+
      '<div class="gempiLocation"><% if( typeof city !="undefined" && city ){ %><%- city %> <% } %></div>'+
      '<div class="gempiDescription"><%=description%></div>'+
      '<% if('+cogumelo.publicConf.mod_detectMobile_isTablet+'){ %><div class="gempiTouchAccess"><button class="buttonTabletAccess  btn btn-primary">Discover</button></div><% } %>'+
    '</div>'+
    '<div class="extraBottomContent"></div>'+

  '</div>';

geozzy.explorerComponents.mapInfoViewMobileTemplate = ''+
'<div class="gempiContent">'+
  '<div class="gempiItem">'+
    '<div class="nextButton"><i class="fas fa-chevron-right"></i></div>' +
    '<div class="previousButton"><i class="fas fa-chevron-left"></i></div>' +
    '<div class="closeButton"><i class="fas fa-times"></i></div>' +

    '<div class="gempiImg accessButton">'+
      '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-img%>/squareCut/<%-img%>.jpg">'+
    '</div>'+
    '<div class="gempiInfo">'+
      '<div class="gempiTitle"><%-title%></div>'+
      '<div class="gempiDescription"><%=description%></div>'+
      '<button class="accessButton">'+__('ACCEDER')+'</button>'+
    '</div>'+
    '<div class="extraBottomContent"></div>'+
  '</div>'+
'</div>';


geozzy.explorerComponents.listEmpty = ''+
  '<div class="explorerListEmpty">'+
     __('No results found')+
  '</div>';

geozzy.explorerComponents.activeListTinyViewTemplate = ''+
  '<div class="explorerListPager">'+
    '<%=pager%>'+
  '</div>'+
  '<div class="explorerListContent container-fluid">'+
    '<div class="row">'+
      '<%=content%>'+
    '</div>'+
  '</div>';

geozzy.explorerComponents.activeListTinyViewElement = ''+
  '<div data-resource-id="<%- id %>" class="col-4 col-sm-2 element element-<%- id %>">'+
    '<div class="elementImg">'+
      '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/wmdpi4/<%- img %>.jpg">'+
      '<div data-resource-id="<%- id %>" class="elementHover accessButton">'+
        '<% if('+cogumelo.publicConf.mod_detectMobile_isTablet+'){ %><button class="buttonTabletAccess btn btn-primary">Discover</button><% } %>'+
      '</div>'+
      '<div class="elementFav">'+
        '<div data-favourite-resource="<%- id %>" data-favourite-status="0" class="rExtFavourite rExtFavouriteHidden">'+
          '<i class="far fa-heart fav-off"></i><i class="fas fa-heart fav-on"></i>'+
        '</div>'+
      '</div>'+
    '</div>'+

    '<div class="elementInfo">'+
      '<%-title%>'+
    '</div>'+
  '</div>';

geozzy.explorerComponents.activeListTinyViewPager = ''+
  '<div class="previous"><i class="fas fa-sort-up"></i></div>'+
    '<% for( c=0 ; c <= pages ; c++){ %>'+
      '<% if(c==v.currentPage){ %>'+
        '<div><span class="currentPage"><i class="far fa-square"></i></span></div>'+
      '<% }else{ %>'+
        '<div><span><i class="fas fa-square pageNum" data-page-num="<%- c %>"></i></span></div>'+
      '<% } %>'+
    '<% } %>'+
  '<div class="next"><i class="fas fa-sort-down"></i></div>';

geozzy.explorerComponents.activeListViewTemplate = ''+
  '<div class="explorerActiveListContent ">'+
    '<div class="container">'+
      '<% if(typeof activeListReset!="undefined" && activeListReset) { %>'+
        '<div class="activeListReset explorerElSticky">' +
          '<i class="fas fa-info info"></i> <%- activeListResetText %> <i class="fas fa-times clear"></i>'+
        '</div>'+
      '<%}%>'+
      '<div class="row">'+
        '<%=content%>'+
      '</div>'+
    '</div>'+
  '</div>';

geozzy.explorerComponents.activeListViewElement = '' +
'<div data-resource-id="<%- id %>" class="col-12 element">'+
  '<div class="elementImg">'+
    '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%- img %>/wmdpi4/<%- img %>.jpg">'+
    '<div data-resource-id="<%- id %>" class="elementHover accessButton">'+
      '<% if('+cogumelo.publicConf.mod_detectMobile_isTablet+'){ %><button class="buttonTabletAccess btn btn-primary">Discover</button><% } %>'+
    '</div>'+
    '<%  if( parseInt(isRoute) == 1 ){ %> ' +
      '<% if( typeof difficultyGlobalText !="undefined" ){ %><div class="routeDifficulty difficulty_<%- difficultyGlobal %>"><%- difficultyGlobalText %></div><% } %>' +
    '<% } %>'+
    '<div class="elementFav">'+
      '<div style="display:none;" data-favourite-resource="<%- id %>" data-favourite-status="0" class="rExtFavourite rExtFavouriteHidden">'+
        '<i class="far fa-heart fav-off"></i><i class="fas fa-heart fav-on"></i>'+
      '</div>'+
    '</div>'+
  '</div>'+
  '<div class="elementInfo">'+
    '<div class="elementTitle"><%-title%></div>'+
    '<div class="elementType"><%- category.name %></div>'+
    /*'<div class="elementLocation">(<% if(ayuntamientoLugar){ %><%-ayuntamientoLugar%>, <% } %><%-appLugarProvinciaName%>)</div>'+*/
  '</div>'+
'</div>';


geozzy.explorerComponents.filterButtonsViewTemplate = "" +
  " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
  "<ul class='clearfix'>"+
    "<% if(defaultOption){ %> "+
      "<li data-term-id='<%- defaultOption.value %>' > "+
        "<div class='title'><%- defaultOption.title %></div> "+
        "<img class='icon' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- defaultOption.icon %>/typeIcon/icon.png'> " +
        "<img class='iconHover' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- defaultOption.icon %>/typeIconHover/iconHover.png'> " +
        "<img class='iconSelected' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- defaultOption.icon %>/typeIconSelected/iconSelected.png'> " +
      "</li>"+
    "<%}%>"+
    "<%= options %>"+
  "</ul>";


geozzy.explorerComponents.filterButtonsViewOption = "" +
  "<li data-term-id='<%- id %>'>"+
    "<div class='title'><%- name %></div> "+
    "<img class='icon' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- icon %>/typeIcon/icon.png'> " +
    "<img class='iconHover' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- icon %>/typeIconHover/iconHover.png'> " +
    "<img class='iconSelected' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- icon %>/typeIconSelected/iconSelected.png'> " +
  "</li>";


geozzy.explorerComponents.filterComboViewTemplate = "" +
  " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
  "<select>"+
    "<% if(defaultOption){ %> <option value='<%- defaultOption.value %>' icon='<%- defaultOption.icon %>'><%- defaultOption.title %></option> <%}%>"+
    "<%= options %>"+
  "</select>";


geozzy.explorerComponents.filterComboViewOptionT =  "<option value='<%- id %>' icon='<%- icon %>'><%- name %></option>";

geozzy.explorerComponents.filterComboViewSummaryT = "" +
  " <% if(title){ %> <label><%= title %>:</label><%}%>  "+
  "<span><img class='icon' src='"+cogumelo.publicConf.mediaHost+"cgmlImg/<%- option.icon %>/typeIcon/icon.png'> <%- option.name %> </span>";


geozzy.explorerComponents.filterResetTemplate = ""+
    "<div>"+
      "<button><%= title %></button> "+
    "</div>";
