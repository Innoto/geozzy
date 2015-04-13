<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="#">Geozzy admin</a>
</div>
<!-- /.navbar-header -->

<ul class="nav navbar-top-links navbar-right">
  <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-bell fa-fw"></i>  <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-alerts">
          <li>
              <a href="#">
                  <div>
                      <i class="fa fa-comment fa-fw"></i> New Comment
                      <span class="pull-right text-muted small">4 minutes ago</span>
                  </div>
              </a>
          </li>
          <li class="divider"></li>
          <li>
              <a href="#">
                  <div>
                      <i class="fa fa-tasks fa-fw"></i> New Task
                      <span class="pull-right text-muted small">4 minutes ago</span>
                  </div>
              </a>
          </li>
          <li class="divider"></li>
          <li>
              <a class="text-center" href="#">
                  <strong>See All Alerts</strong>
                  <i class="fa fa-angle-right"></i>
              </a>
          </li>
      </ul>
      <!-- /.dropdown-alerts -->
  </li>
  <!-- /.dropdown -->
  <li class="dropdown">
      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
          <i class="fa fa-user fa-fw"></i>  {$user->getter('login')} <i class="fa fa-caret-down"></i>
      </a>
      <ul class="dropdown-menu dropdown-user">
          <li><a href="/admin#user/show"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
          <li><a href="/admin#user/edit/{$user->getter('id')}"><i class="fa fa-edit fa-fw"></i> Edit Profile</a></li>
          <li class="divider"></li>
          <li><a href="/admin/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
      </ul>
      <!-- /.dropdown-user -->
  </li>
  <!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->



