var geozzy = geozzy || {};
if(!geozzy.adminStoryComponents) geozzy.adminStoryComponents={};

geozzy.adminStoryComponents.StoryTemplate = ''+
'<div id="menuStories">'+
  '<ul class="nav nav-second-level storiesList collapse in" aria-expanded="true" style="">'+
    '<li class="stories story_<%- id %>">'+
      '<a href="/admin#storysteps/<%- id %>"><i class="fa fa-eye fa-fw"></i> <%- title_'+cogumelo.publicConf.langDefault+' %> </a>'+
    '</li>'+
  '</ul>'+
'</div>';

geozzy.adminStoryComponents.InfowindowPOI = ''+
'<div class="poiInfoWindow">'+
  '<div class="poiImg">'+
    '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>/squareCut/<%-image%>.jpg" />'+
  '</div>'+
  '<div class="poiInfo">'+
    '<div class="poiTitle"><p><%-title%></p></div>'+
    '<div class="poiDescription"><%-description%></div>'+
    '<% if( isNormalResource == 1 ) { %> <a target="_blank" href="/resource/<%-id%>" ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>'

  '</div>'
'</div>';
