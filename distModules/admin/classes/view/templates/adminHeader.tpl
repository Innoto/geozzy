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
          <li><a href="/admin/user/show"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
          <li><a href="/admin/user/edit/{$user->getter('id')}"><i class="fa fa-edit fa-fw"></i> Edit Profile</a></li>
          <li class="divider"></li>
          <li><a href="/admin/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
      </ul>
      <!-- /.dropdown-user -->
  </li>
  <!-- /.dropdown -->
</ul>
<!-- /.navbar-top-links -->

<div class="navbar-default sidebar" role="navigation">
  <div class="sidebar-nav navbar-collapse">
      <ul class="nav" id="side-menu">

          <!-- TOPIC -->
          <li class="topics">
              <a href="/admin"><i class="fa fa-map-marker fa-fw fa-2x"></i>Topic 1</a>
          </li>

          <li class="topics">
              <a href="/admin"><i class="fa fa-compass fa-fw fa-2x"></i>Topic 2</a>
          </li>

          <li class="topics">
              <a href="/admin"><i class="fa fa-tree fa-fw fa-2x"></i>Topic 3</a>
          </li>
          <!-- END TOPICS -->

          <li>
              <a class="active" href="/admin#charts"><i class="fa fa-line-chart fa-fw"></i> Charts</a>
          </li>

          <li>
              <a href="/admin"><i class="fa fa-files-o fa-fw"></i> Pages</a>
          </li>

          <!-- Labels -->
          <li>
              <a href="#"><i class="fa fa-tags fa-fw"></i> Categories <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                {foreach from=$taxs item=tax}
                {if $tax->getter('idName') != "Destacado" }
                  <li>
                      <a href="/admin#taxonomygroup/{$tax->getter('id')}"><i class="fa fa-tag fa-fw"></i> {$tax->getter('idName')} </a>
                  </li>
                {/if}
                {/foreach}
              </ul>
              <!-- /.nav-second-level -->
          </li>
          <li>
              <a href="/admin/taxonomygroup/{$taxDestacado->getter('id')}"><i class="fa fa-star fa-fw"></i> {$taxDestacado->getter('idName')} </a>
          </li>

          <!-- Settings -->
          <li>
              <a href="#"><i class="fa fa-cog fa-fw"></i> Settings <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">
                  <li>
                      <a href="/admin/alltables"><i class="fa fa-table fa-fw"></i> Tables</a>
                  </li>
                  <li>
                      <a href="/admin/addcontent"><i class="fa fa-edit fa-fw"></i> Add Content</a>
                  </li>
                  <li>
                      <a href="#"><i class="fa fa-users fa-fw"></i> Users <span class="fa arrow"></span></a>
                      <ul class="nav nav-third-level">
                        <li>
                          <a href="/admin#user/list"><i class="fa fa-user fa-fw"></i> User</a>
                        </li>
                        <li>
                          <a href="/admin#role/list"><i class="fa fa-tag fa-fw"></i> Roles</a>
                        </li>

                      </ul>
                      <!-- /.nav-third-level -->
                  </li>
              </ul>
              <!-- /.nav-second-level -->
          </li>
      </ul>
  </div>
  <!-- /.sidebar-collapse -->
</div>
<!-- /.navbar-static-side -->