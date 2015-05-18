<!DOCTYPE html>
<html lang="es">
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Geozzy Admin</title>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="/vendor/bower/html5shiv/dist/html5shiv.js"></script>
      <script src="/vendor/bower/respond/dest/respond.min.js"></script>
  <![endif]-->

  {$css_includes}
  {$js_includes}

</head>

<body>

  <!-- Client templates -->
  {include file="admin///categoryEditor.tpl"}


  <div id="wrapper">
    <div id="menu-wrapper">
      <div id="menuInfo">
        <div class="menuLogo">
          <img src="media/module/geozzy/img/logo.png" class="img-responsive">
        </div>
        <ul class="userInfo nav">
          <li class="dropdown">
              <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                {$user->getter('login')}
                <i class="fa fa-caret-down"></i>
              </a>
              <ul class="dropdown-menu dropdown-user">
                  <li><a href="/admin#user/show"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                  <li><a href="/admin#user/edit/{$user->getter('id')}"><i class="fa fa-edit fa-fw"></i> Edit Profile</a></li>
                  <li class="divider"></li>
                  <li><a href="/admin/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
              </ul>
              <!-- /.dropdown-user -->
          </li>
        </ul>
      </div>
      {include file="admin///adminMenu.tpl"}
    </div>
    <div id="page-wrapper"><!--Content -->
    </div><!-- /#page-wrapper -->
  </div><!-- /#wrapper -->

</body>

</html>
