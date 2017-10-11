<nav id="menu-wrapper" class="navbar clearfix" role="navigation">
  <div class="sidebar" role="navigation">
    <div class="sidebar-nav collapse navbar-collapse offcanvas">
        <div id="menuInfo">
          <div class="menuLogo">
            <a href="/">
              {if !isset($logoCustom)}
                <img src="{$cogumelo.publicConf.media}/module/geozzy/img/logo.png" class="img-responsive">
              {else}
                <img src="{$cogumelo.publicConf.media}{$logoCustom}" class="img-responsive">
              {/if}
            </a>
          </div>
          <ul class="userInfo nav">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                  {if array_key_exists('avatar', $user['data'])}
                    <img class="userAvatar img-responsive" src="{$cogumelo.publicConf.mediaHost}cgmlImg/{$user['data']['avatar']}-a{$user['data']['avatarAKey']}/fast_cut/{$user['data']['avatarName']}">
                  {/if}
                  {$user['data']['login']}
                  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                  {if $userPermission}
                    <li><a href="/admin#user/show"><i class="fa fa-user fa-fw"></i> {t}User Profile{/t}</a></li>
                    <li><a href="/admin#user/edit/id/{$user['data']['id']}"><i class="fa fa-edit fa-fw"></i>{t}Edit Profile{/t}</a></li>
                  {/if}
                    <li class="divider"></li>
                    <li><a href="/admin/logout"><i class="fa fa-sign-out fa-fw"></i>{t}Logout{/t}</a></li>
                </ul>
                <!-- /.dropdown-user -->
            </li>
          </ul>
        </div>

        <!-- Navigation -->
        <ul class="nav clearfix" id="side-menu">
          <!-- TOPIC -->
          <script type="text/template" id="menuTopics">
          {if $topicPermission}
          <% _.each(topics, function(topic) { %>
            <li class="topics topic_<%- topic.id %>">
              <a href="/admin#topic/<%- topic.id %>"><i class="fa fa-star fa-fw"></i> <%- topic.name_{$cogumelo.publicConf.langDefault} %> </a>
            </li>
          <% }); %>
          {/if}
          </script>
          <!-- END TOPICS -->

          {if isset($biInclude)}
            <li class="charts">
              <a  href="/admin#charts"><i class="fa fa-line-chart fa-fw"></i> {t}Charts{/t}</a>
            </li>
          {/if}
          {if $pagePermission}
          <li class="pages">
            <a href="/admin#resourcepage/list"><i class="fa fa-files-o fa-fw"></i> {t}Pages{/t} </a>
          </li>
          {/if}


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

          <li class="menu">
            <a href="/admin#menu"><i class="fa fa-bars fa-fw" aria-hidden="true"></i> {t}Menu{/t} </a>
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
            <a href="#"><i class="fa fa-tags fa-fw"></i> {t}Categories{/t} <span class="fa arrow"></span></a>
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
          {if $settingPermission}
          <li>
            <a href="#"><i class="fa fa-cog fa-fw"></i> {t}Settings{/t} <span class="fa arrow"></span></a>
            <ul class="nav nav-second-level">
              <!--<li class="menu">
                <a href="/admin#"><i class="fa fa-bars fa-fw"></i> {t}Menu{/t} </a>
              </li>-->
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
          {/if}
        </ul> <!-- /side-menu -->
    </div>
  </div>
</nav>


<a href="#" class="btn btn-primary pull-left" id="menu-toggle">
  <!--<i class="fa fa-bars" aria-hidden="true"></i>-->
  <i class="fa fa-angle-double-left opened" aria-hidden="true"></i>
  <i class="fa fa-angle-double-right closed" aria-hidden="true"></i>
</a>
