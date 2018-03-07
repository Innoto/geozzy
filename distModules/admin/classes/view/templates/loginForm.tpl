<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Innoto">

    <title>Log in Geozzy Admin</title>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="/vendor/bower/html5shiv/dist/html5shiv.js"></script>
        <script src="/vendor/bower/respond/dest/respond.min.js"></script>
    <![endif]-->
    {$main_client_includes}
    {$client_includes}

</head>

<body class="adminLogin">

  <div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
        <div class="login-panel panel panel-default">
          <div class="panel-heading">
              <h3 class="panel-title">Log In</h3>
          </div>
          <div class="panel-body">
            {$loginHtml}

            <a class="initRecoveryPass">{t}He olvidado mi contraseña{/t}</a>
            <div class="recoveryPasswordForm" style="display:none;">
              <h3>{t}Recuperar contraseña{/t}</h3>
              <p>{t}Introduce la dirección de correo electrónico asociada a tu cuenta y te enviaremos un enlace para restablecer tu contraseña{/t}</p>
              <form onsubmit="return false;"><div class="cgmMForm-wrap"><input type="text" class="recoveryPassEmail" placeholder="Email"></div>
              <div class="cgmMForm-wrap"><input value="{t}Recuperar{/t}" type="button" class="recoveryPassSubmit btn btnBecarios pull-right"></div></form>
            </div>
            <div class="recoveryPasswordFinalMsg" style="display:none;">
              {t}Se ha enviado un correo electrónico para recuperar tu cuenta de usuario.{/t}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
