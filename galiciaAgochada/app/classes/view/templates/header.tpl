<nav role="navigation" class="navbar navbar-default  clearfix">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
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
        {if isset($isFront) && $isFront}
        <a href="#inicio" class="navbar-brand page-scroll">
          <img alt="logo" class="logo img-responsive" src="{$cogumelo.publicConf.media}/img/logoGA.png"/>
        </a>
        {else}
        <a href="/{$cogumelo.publicConf.C_LANG}/#inicio" class="navbar-brand page-scroll">
          <img alt="logo" class="logo img-responsive" src="{$cogumelo.publicConf.media}/img/logoGA.png"/>
        </a>
        {/if}
      </div>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->
      <div class="col-md-9 col-lg-9">
      <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav navbar-right">

          {if isset($isFront) && $isFront}
            <li><a href="#inicio" class="page-scroll">{t}Inicio{/t}</a></li>
            <li><a href="#segredos" class="page-scroll">{t}Segredos{/t}</a></li>
            <li><a href="#participa" class="page-scroll">{t}Participa{/t}</a></li>
            <li><a href="#recomendamos" class="page-scroll">{t}Recomendamos{/t}</a></li>
            <li><a href="#" class="page-scroll">{t}Acerca de{/t}</a></li>
          {else}
            <li><a href="/{$cogumelo.publicConf.C_LANG}/#inicio" class="page-scroll">{t}Inicio{/t}</a></li>
            <li><a href="/{$cogumelo.publicConf.C_LANG}/#segredos" class="page-scroll">{t}Segredos{/t}</a></li>
            <li><a href="/{$cogumelo.publicConf.C_LANG}/#participa" class="page-scroll">{t}Participa{/t}</a></li>
            <li><a href="/{$cogumelo.publicConf.C_LANG}/#recomendamos" class="page-scroll">{t}Recomendamos{/t}</a></li>
            <li><a href="/{$cogumelo.publicConf.C_LANG}/#sobre-nosotros" class="page-scroll">{t}Acerca de{/t}</a></li>
          {/if}

          <li class="dropdown langSelector">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-globe"></i>&nbsp;{$cogumelo.publicConf.C_LANG}</a>
            <ul class="dropdown-menu">
              {if isset($res.data) && $res.data} <!-- recurso -->
                {foreach key=k item=lang from=$cogumelo.publicConf.lang_available}
                  {if $cogumelo.publicConf.C_LANG!=$k}<li><a href="/{$k}{$res.data["urlAlias_$k"]}" class="page-scroll">{$lang.name}</a></li>{/if}
                {/foreach}
              {else} <!-- sitios donde no hay recurso aún (portada y exploradores) -->
                {if !isset($url) || (isset($isFront) && $isFront)}
                  {foreach key=k item=lang from=$cogumelo.publicConf.lang_available}
                    {if $cogumelo.publicConf.C_LANG!=$k}<li><a href="/{$k}" class="page-scroll">{$lang.name}</a></li>{/if}
                  {/foreach}
                {else}
                  {foreach key=k item=lang from=$cogumelo.publicConf.lang_available}
                    {if $cogumelo.publicConf.C_LANG!=$k}<li><a href="/{$k}/{$url}" class="page-scroll">{$lang.name}</a></li>{/if}
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
