var geozzy = geozzy || {};
if(!geozzy.storystepsComponents) geozzy.storystepsComponents={};

geozzy.storystepsComponents.StorystepTemplate = ''+
    '<li class="dd-item" data-id="<%- id %>">'+
      '<div class="dd-item-container clearfix">'+
        '<div class="dd-content">'+
          '<div class="nestableActions">'+
            '<button class="btnEditStoryStep btn-icon btn-info" data-id="<%- id %>" ><i class="fa fa-pencil"></i></button>'+
            '<button class="btnDelete btn-icon btn-danger" data-id="<%- id %>" ><i class="fa fa-trash"></i></button>'+
          '</div>'+
        '</div>'+
        '<div class="dd-handle">'+
          '<i class="fa fa-arrows icon-handle"></i>'+
          '<%- title %>'+
        '</div>'+
      '</div>'+
    '</li>';
