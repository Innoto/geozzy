var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};


geozzy.travelPlannerComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<img class="iconModal img-responsive" src="'+cogumelo.publicConf.media+'/img/iconModal.png"></img>'+
        //'<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
    '</div>'+
  '</div>'+
'</div>';

geozzy.travelPlannerComponents.travelPlannerInterfaceTemplate = ''+
'<div class="travelPlanner">'+
  '<div class="travelPlannerList">'+
    '<div class="travelPlannerFilters"></div>'+
    '<div class="travelPlannerResources"></div>'+
  '</div>'+
  '<div class="travelPlannerPlan">'+

  '</div>'+
'</div>';
