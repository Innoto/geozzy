<nav id="menu-wrapper" class="navbar clearfix" role="navigation">
  <div class="sidebar" role="navigation">
    <div class="sidebar-nav collapse navbar-collapse ">

        <div id="menuInfo">
          <div class="menuLogo">
            <img src="{$cogumelo.publicConf.media}/module/geozzy/img/logo.png" class="img-responsive">
          </div>
          <ul class="userInfo nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  {if array_key_exists('avatar', $user['data'])}
                    <img class="userAvatar img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$user['data']['avatar']}">
                  {/if}
                  {$user['data']['login']}
                  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="/admin#user/show"><i class="fa fa-user fa-fw"></i> User Profile</a></li>
                    <li><a href="/admin#user/edit/id/{$user['data']['id']}"><i class="fa fa-edit fa-fw"></i> Edit Profile</a></li>
                    <li class="divider"></li>
                    <li><a href="/admin/logout"><i class="fa fa-sign-out fa-fw"></i> Logout</a></li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
          </ul>
        </div>

        <!-- Navigation -->
        <ul class="nav clearfix" id="side-menu">
          <!-- TOPIC -->
          <script type="text/template" id="menuTopics">
          <% _.each(topics, function(topic) { %>
            {if $topicPermission}
               <li class="topics topic_<%- topic.id %>">
                  <a href="/admin#topic/<%- topic.id %>"><i class="fa fa-star fa-fw"></i> <%- topic.name_{$cogumelo.publicConf.langDefault} %> </a>
               </li>
            {/if}
          <% }); %>
          </script>
          <!-- END TOPICS -->

          {if isset($biInclude)}
            <li class="charts">
              <a  href="/admin#charts"><i class="fa fa-line-chart fa-fw"></i> {t}Charts{/t}</a>
            </li>
          {/if}
          <li class="pages">
            <a href="/admin#resourcepage/list"><i class="fa fa-files-o fa-fw"></i> {t}Pages{/t} </a>
          </li>

          <li class="starred">
            <a href="/admin#starred"><i class="fa fa-star fa-fw"></i> {t}Starred{/t} <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level starredList">
                 <!-- TOPIC -->
                <script type="text/template" id="menuStarred">
                <% _.each(starred, function(star) { %>
                     <li class="starred star_<%- star.id %>">
                        <a href="/admin#starred/<%- star.id %>"><i class="fa fa-star fa-fw"></i> <%- star.name_{$cogumelo.publicConf.langDefault} %> </a>
                     </li>
                <% }); %>
                </script>
                <!-- END TOPICS -->
              </ul>
          </li>

          {if isset($rextCommentInclude)}
            <li class="comments">
              <a href="/admin#comment/list"><i class="fa fa-comments-o fa-fw"></i> {t}Comments{/t}</a>
            </li>
          {/if}

          {if isset($rextCommentInclude)}
            <li class="suggestions">
              <a href="/admin#suggestion/list"><i class="fa fa-commenting-o fa-fw"></i> {t}Suggestions{/t}</a>
            </li>
          {/if}

          {if $superAdminPermission}
          <li class="contents">
            <a href="/admin#resource/list"><i class="fa fa-indent fa-fw"></i> {t}Contents{/t} </a>
          </li>
          {/if}
          <!-- Categories -->
          <li class="categories">
            <a href="#"><i class="fa fa-tags fa-fw"></i> Categories <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level categoriesList">
                <script type="text/template" id="menuCategoryElement">
                  <% for(var categoryK in categories) { %>
                    <li class="category_<%- categories[categoryK].id %>">
                      <a href="/admin#category/<%- categories[categoryK].id %>"><i class="fa fa-tag fa-fw"></i> <%- categories[categoryK].name_{$cogumelo.publicConf.langDefault} %> </a>
                    </li>
                  <% } %>
                </script>
              </ul>
              <!-- /.nav-second-level -->
          </li>
          <!-- Settings -->
          <li>
            <a href="#"><i class="fa fa-cog fa-fw"></i> {t}Settings{/t} <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
              <li class="menu">
                <a href="/admin#"><i class="fa fa-bars fa-fw"></i> {t}Menu{/t} </a>
              </li>
              {if $userPermission}
              <li>
                <a href="#"><i class="fa fa-users fa-fw"></i> {t}Users{/t} <span class="fa arrow"></span></a>
                  <ul class="nav nav-third-level">
                    <li class="user">
                      <a href="/admin#user/list"><i class="fa fa-user fa-fw"></i> {t}User{/t} </a>
                    </li>
                    <li class="roles">
                      <a href="/admin#role/list"><i class="fa fa-tag fa-fw"></i> {t}Roles{/t}</a>
                    </li>

                  </ul>
                  <!-- /.nav-third-level -->
              </li>
              {/if}
            </ul>
            <!-- /.nav-second-level -->
          </li>
        </ul> <!-- /side-menu -->
    </div>
  </div>
</nav>
