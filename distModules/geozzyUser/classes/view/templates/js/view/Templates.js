var geozzy = geozzy || {};
if(!geozzy.userSessionComponents) geozzy.userSessionComponents={};


geozzy.userSessionComponents.modalMdTemplate = ''+
'<div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">'+
  '<div class="modal-dialog modal-md">'+
    '<div class="modal-content">'+
      '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>'+
        '<h3 class="modal-title"><%- modalTitle %></h3>'+
      '</div>'+
      '<div class="modal-body"></div>'+
      '<div class="modal-footer">'+
        '<button type="button" class="btn btn-warning" data-dismiss="modal">'+__("Close")+'</button>'+
      '</div>'+
    '</div>'+
  '</div>'+
'</div>';

geozzy.userSessionComponents.userLoginBoxTemplate = ''+
'<h3>'+__("Necesitas tener una cuenta para participar en Galicia Agochada")+'</h3>'+
'<button type="button" class="gotoregister btn btn-primary">'+__("Crear una cuenta")+'</button>'+
'<hr />'+
'<h3>'+__("¿Ya tienes una cuenta?")+'</h3>'+
'<h3>'+__("Inicia tu sesión para continuar")+'</h3>'+
'<div class="loginModalForm"></div>'+
'<a href="#">'+__("He olvidado mi contraseña")+'</a>';

geozzy.userSessionComponents.userRegisterBoxTemplate = ''+
'<h3>'+__("Crear una cuenta en Galicia Agochada es muy fácil. Rellena el siguiente formulario")+'.</h3>'+
'<div class="registerModalForm"></div>';



geozzy.userSessionComponents.userRegisterOkBoxTemplate = ''+
'<h3>'+__("Muchas gracias")+'.</h3>'+
'<h3>'+__("Tu cuenta se ha creado con éxito")+'</h3>'+
'<p>'+__("En breve recibirás un correo-e para comprobar tu identidad")+'.</p>'+
'<p>'+__("Si no lo recibes en las proximas horas, comprueba tu bandeja de SPAM")+'</p>'+
'<button type="button" class="btn btn-primary" data-dismiss="modal">'+__("Continuar")+'</button>';
