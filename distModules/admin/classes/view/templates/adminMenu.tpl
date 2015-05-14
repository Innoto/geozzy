<!-- Navigation -->
<nav class="navbar navbar-default" role="navigation">
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
          <a class="active" href="/admin#charts"><i class="fa fa-line-chart fa-fw"></i> {t}Charts{/t}</a>
        </li>
        <li>
          <a href="/admin"><i class="fa fa-files-o fa-fw"></i> {t}Pages{/t} </a>
        </li>
        <li>
          <a href="/admin#starred/"><i class="fa fa-star fa-fw"></i> {t}Starred{/t} </a>
        </li>
        <li>
          <a href="/admin#resource/list/"><i class="fa fa-indent fa-fw"></i> {t}Contents{/t} </a>
        </li>

        <!-- Settings -->
        <li>
          <a href="#"><i class="fa fa-cog fa-fw"></i> {t}Settings{/t} <span class="fa arrow"></span></a>
          <ul class="nav nav-second-level">
            <li>
              <a href="/admin#"><i class="fa fa-bars fa-fw"></i> {t}Menu{/t} </a>
            </li>
            <li>
              <a href="#"><i class="fa fa-tags fa-fw"></i> Categories <span class="fa arrow"></span></a>
                <ul class="nav nav-third-level categoriesList">
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
              <a href="#"><i class="fa fa-users fa-fw"></i> {t}Users{/t} <span class="fa arrow"></span></a>
                <ul class="nav nav-third-level">
                  <li>
                    <a href="/admin#user/list"><i class="fa fa-user fa-fw"></i> {t}User{/t} </a>
                  </li>
                  <li>
                    <a href="/admin#role/list"><i class="fa fa-tag fa-fw"></i> {t}Roles{/t}</a>
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
