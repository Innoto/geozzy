<script type="text/template" id="modalMdTemplate">
  <div id="<%- modalId %>" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h3 class="modal-title"><%- modalTitle %></h3>
        </div>
        <div class="modal-body"></div>
        <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/template" id="userLoginBox">

  <h3>{t}Necesitas tener una cuenta para participar en Galicia Agochada{/t}</h3>
  <button type="button" class="gotoregister btn btn-primary">{t}Crear una cuenta{/t}</button>
  <hr></hr>
  <h3>{t}¿Ya tienes una cuenta?{/t}</h3>
  <h3>{t}Inicia tu sesión para continuar{/t}</h3>
  <div class="loginForm"></div>
  <a href="#">{t}He olvidado mi contraseña{/t}</a>

</script>

<script type="text/template" id="userRegisterBox">
  <div class="container">
    <h3>{t}Crear una cuenta en Galicia Agochada es muy fácil. Rellena el siguiente formulario{/t}.</h3>
    <%- formRegister %>
  </div>
</script>

<script type="text/template" id="registerOkBox">
  <div class="container">
    <h3>{t}Muchas gracias{/t}.</h3>
    <h3>{t}Tu cuenta se ha creado con éxito{/t}</h3>
    <p>{t}En breve recibirás un correo-e para comprobar tu identidad{/t}.</p>
    <p>{t}Si no lo recibes en las proximas horas, comprueba tu bandeja de SPAM{/t}</p>
    <button type="button" class="btn btn-primary" data-dismiss="modal">{t}Continuar{/t}</button>

  </div>
</script>
