<!DOCTYPE html>
<html lang="es">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Geozzy Admin</title>

  <!-- Timeline CSS -->
  <!--<link href="css/plugins/timeline.css" rel="stylesheet">-->


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
  {include file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/categoryEditor.tpl"}

  <div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
      {include file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/adminHeader.tpl"}
      <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">

                <!-- TOPIC -->

                <script type="text/template" id="menuTopics">

                <% _.each(topics, function(topic) { %>
                      <li class="topics">
                        <a href="/admin#topic/<%- topic.id %>"><i class="fa fa-star fa-fw"></i> <%- topic.name %> </a>
                     </li>
                <% }); %>

                </script>
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
                    <ul class="nav nav-second-level categoriesList">
                      <script type="text/template" id="menuCategoryElement">

                        <% for(var categoryK in categories) { %>
                          <li>
                              <a href="/admin#category/<%- categories[categoryK].id %>"><i class="fa fa-tag fa-fw"></i> <%- categories[categoryK].name %> </a>
                          </li>
                        <% } %>

                      </script>
                    </ul>
                    <!-- /.nav-second-level -->
                </li>
                <li>
                    <a href="/admin#starred/"><i class="fa fa-star fa-fw"></i> Destacados </a>
                </li>

                <!-- Settings -->
                <li>
                    <a href="#"><i class="fa fa-cog fa-fw"></i> Settings <span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level">
                        <li>
                            <a href="/admin#alltables"><i class="fa fa-table fa-fw"></i> Tables</a>
                        </li>
                        <li>
                            <a href="/admin#addcontent"><i class="fa fa-edit fa-fw"></i> Add Content</a>
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

    </nav>

    <div id="page-wrapper">
    </div>
      <!-- /#page-wrapper -->
  </div>
  <!-- /#wrapper -->


</body>

</html>
