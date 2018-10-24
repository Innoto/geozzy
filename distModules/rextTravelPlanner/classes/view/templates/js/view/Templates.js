var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};


geozzy.travelPlannerComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<img class="iconModal img-fluid" src="'+cogumelo.publicConf.media+'/img/iconModal.png"></img>'+
        //'<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
    '</div>'+
  '</div>'+
'</div>';


geozzy.travelPlannerComponents.modalFullTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-lg">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<img class="iconModal img-fluid" src="'+cogumelo.publicConf.media+'/img/iconModal.png"></img>'+
        //'<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
    '</div>'+
  '</div>'+
'</div>';
