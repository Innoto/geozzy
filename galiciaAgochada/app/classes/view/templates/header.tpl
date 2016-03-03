<nav role="navigation" class="navbar navbar-default navbar-fixed-top clearfix">
  <div class="container">
    <div class="row">
      <div class="col-sm-12 col-md-3 col-lg-3">
        <div class="navbar-header">
          <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <!-- LOGO in brand section -->
          {if isset($res.data) && $res.data["urlAlias"]=="/"}
            <a href="#inicio" class="navbar-brand page-scroll">
              <img alt="logo" class="logo img-responsive" src="{$cogumelo.publicConf.media}/img/logoGA.png"/>
            </a>
          {else}
            <a href="/{$GLOBAL_C_LANG}/#inicio" class="navbar-brand page-scroll">
              <img alt="logo" class="logo img-responsive" src="{$cogumelo.publicConf.media}/img/logoGA.png"/>
            </a>
          {/if}
        </div>
      </div>
      <div class="col-md-9 col-lg-9">

        <div class="collapse navbar-collapse">
          <ul class="nav navbar-nav navbar-right">
            {if isset($res.data) && $res.data["urlAlias"]=="/"}
              <li><a href="#inicio" class="page-scroll">{t}Inicio{/t}</a></li>
              <li><a href="#segredos" class="page-scroll">{t}Segredos{/t}</a></li>
              <li><a href="#participa" class="page-scroll">{t}Participa{/t}</a></li>
              <li><a href="#recomendamos" class="page-scroll">{t}Recomendamos{/t}</a></li>
              <li><a href="#" class="page-scroll">{t}Acerca de{/t}</a></li>
            {else}
              <li><a href="/{$GLOBAL_C_LANG}/#inicio" class="page-scroll">{t}Inicio{/t}</a></li>
              <li><a href="/{$GLOBAL_C_LANG}/#segredos" class="page-scroll">{t}Segredos{/t}</a></li>
              <li><a href="/{$GLOBAL_C_LANG}/#participa" class="page-scroll">{t}Participa{/t}</a></li>
              <li><a href="/{$GLOBAL_C_LANG}/#recomendamos" class="page-scroll">{t}Recomendamos{/t}</a></li>
              <li><a href="/{$GLOBAL_C_LANG}/#sobre-nosotros" class="page-scroll">{t}Acerca de{/t}</a></li>
            {/if}

            <li class="dropdown langSelector">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-globe"></i>&nbsp;{$GLOBAL_C_LANG}</a>
              <ul class="dropdown-menu">
                {if isset($res.data) && $res.data}
                  {foreach key=k item=lang from=$GLOBAL_LANG_AVAILABLE}
                    {if $GLOBAL_C_LANG!=$k}<li><a href="/{$k}{$res.data["urlAlias_$k"]}">{$lang.name}</a></li>{/if}
                  {/foreach}
                {else}

                  {if !isset($url) || (isset($isFront) && $isFront)}
                    {foreach key=k item=lang from=$GLOBAL_LANG_AVAILABLE}
                      {if $GLOBAL_C_LANG!=$k}<li><a href="/{$k}">{$lang.name}</a></li>{/if}
                    {/foreach}
                  {else}
                    {foreach key=k item=lang from=$GLOBAL_LANG_AVAILABLE}
                      {if $GLOBAL_C_LANG!=$k}<li><a href="/{$k}/{$url}">{$lang.name}</a></li>{/if}
                    {/foreach}
                  {/if}
                {/if}
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</nav>
