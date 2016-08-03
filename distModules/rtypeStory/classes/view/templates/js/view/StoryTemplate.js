var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryTemplate = ''+
'<div id="menuStories">'+
  '<ul class="nav nav-second-level storiesList collapse in" aria-expanded="true" style="">'+
    '<li class="stories story_<%- id %>">'+
      '<a href="/admin#storysteps/<%- id %>"><i class="fa fa-eye fa-fw"></i> <%- title_'+cogumelo.publicConf.langDefault+' %> </a>'+
    '</li>'+
  '</ul>'+
'</div>'
