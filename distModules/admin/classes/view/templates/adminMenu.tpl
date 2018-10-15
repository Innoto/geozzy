<nav id="menu-wrapper" class="navbar clearfix navbar-expand-lg" role="navigation">
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
          <ul class="userInfo nav navbar-nav">
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
        <ul class="nav navbar-nav clearfix" id="side-menu">
          <!-- TOPIC -->
          <script type="text/template" id="menuTopics">
          {if $topicPermission}
          <% _.each(topics, function(topic) { %>
            <li class="nav-item topics topic_<%- topic.id %>">
              <a class="nav-link" href="/admin#topic/<%- topic.id %>"><i class="fa fa-star fa-fw"></i> <%- topic.name_{$cogumelo.publicConf.langDefault} %> </a>
            </li>
          <% }); %>
          {/if}
          </script>
          <!-- END TOPICS -->

          {if isset($biInclude)}
            <li class="nav-item charts">
              <a class="nav-link" href="/admin#charts"><i class="fa fa-line-chart fa-fw"></i> {t}Charts{/t}</a>
            </li>
          {/if}
          {if $pagePermission}
          <li class="nav-item pages">
            <a class="nav-link" href="/admin#resourcepage/list"><i class="fa fa-files-o fa-fw"></i> {t}Pages{/t} </a>
          </li>
          {/if}


          <li class="nav-item starred">
            <a class="nav-link" href="/admin#starred"><i class="fa fa-star fa-fw"></i> {t}Starred{/t} <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level starredList">
                 <!-- TOPIC -->
                <script type="text/template" id="menuStarred">
                <% _.each(starred, function(star) { %>
                     <li class="nav-item starred star_<%- star.id %>">
                        <a class="nav-link" href="/admin#starred/<%- star.id %>"><i class="fa fa-star fa-fw"></i> <%- star.name_{$cogumelo.publicConf.langDefault} %> </a>
                     </li>
                <% }); %>
                </script>
                <!-- END TOPICS -->
              </ul>
          </li>

          <li class="nav-item menu">
            <a class="nav-link" href="/admin#menu"><i class="fa fa-bars fa-fw" aria-hidden="true"></i> {t}Menu{/t} </a>
          </li>

          {if isset($rextCommentInclude)}
            <li class="nav-item comments">
              <a class="nav-link" href="/admin#comment/list"><i class="fa fa-comments-o fa-fw"></i> {t}Comments{/t}</a>
            </li>
          {/if}

          {if isset($rextCommentInclude)}
            <li class="nav-item suggestions">
              <a class="nav-link" href="/admin#suggestion/list"><i class="fa fa-commenting-o fa-fw"></i> {t}Suggestions{/t}</a>
            </li>
          {/if}

          {if $superAdminPermission}
            <li class="nav-item contents">
              <a class="nav-link" href="/admin#resource/list"><i class="fa fa-indent fa-fw"></i> {t}Contents{/t} </a>
            </li>

            <li class="nav-item translates">
              <a class="nav-link" href="#"><i class="fa fa-exchange fa-fw"></i> {t}Translates{/t} <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level">

                  <li class="nav-item transExport">
                    <a class="nav-link" href="#"><i class="fa fa-long-arrow-left fa-fw"></i> {t}Export{/t} <span class="fa arrow"></span></a>
                    <ul class="nav nav-third-level">
                      <li class="nav-item transExpRes"><a href="/admin#translates/export/resources"> {t}Resources{/t}</a></li>
                      <li class="nav-item transExpColl"><a href="/admin#translates/export/collections"> {t}Collections{/t}</a></li>
                    </ul>
                  </li>
                  <li class="nav-item transImport">
                    <a class="nav-link" href="#"><i class="fa fa-long-arrow-right fa-fw"></i> {t}Import{/t} <span class="fa arrow"></span></a>
                    <ul class="nav nav-third-level">
                      <li class="nav-item transImpFiles"><a class="nav-item" href="/admin#translates/import/files"> {t}Files{/t}</a></li>
                    </ul>
                  </li>

              </ul>
            </li>
          {/if}
          <!-- Categories -->
          <li class="nav-item categories">
            <a class="nav-link" href="#"><i class="fa fa-tags fa-fw"></i> {t}Categories{/t} <span class="fa arrow"></span></a>
              <ul class="nav nav-second-level categoriesList">
                <script type="text/template" id="menuCategoryElement">
                  <% for(var categoryK in categories) { %>
                    <li class="nav-item category_<%- categories[categoryK].id %>">
                      <a class="nav-link" href="/admin#category/<%- categories[categoryK].id %>"><i class="fa fa-tag fa-fw"></i> <%- categories[categoryK].name_{$cogumelo.publicConf.langDefault} %> </a>
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
              <li class="nav-item">
                <a class="nav-link" href="#"><i class="fa fa-users fa-fw"></i> {t}Users{/t} <span class="fa arrow"></span></a>
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
