<nav role="navigation" class="navbar navbar-default  clearfix">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header ">
      <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- LOGO in brand section -->
      {if isset($isFront) && $isFront}
      <a href="#inicio" class="navbar-brand page-scroll">
        <img alt="logo" class="logo img-responsive" src="/media/img/logoGA.png"></img>
      </a>
      {else}
      <a href="{$site_host}#inicio" class="navbar-brand page-scroll">
        <img alt="logo" class="logo img-responsive" src="/media/img/logoGA.png"></img>
      </a>
      {/if}
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse ">
      <ul class="nav navbar-nav navbar-right">

        {if isset($isFront) && $isFront}
          <li><a href="#inicio" class="page-scroll">{t}Inicio{/t}</a></li>
          <li><a href="#segredos" class="page-scroll">{t}Segredos{/t}</a></li>
          <li><a href="#participa" class="page-scroll">{t}Participa{/t}</a></li>
          <li><a href="#recomendamos" class="page-scroll">{t}Recomendamos{/t}</a></li>
          <li><a href="#" class="page-scroll">{t}Acerca de{/t}</a></li>
        {else}
          <li><a href="{$site_host}#inicio" class="page-scroll">{t}Inicio{/t}</a></li>
          <li><a href="{$site_host}#segredos" class="page-scroll">{t}Segredos{/t}</a></li>
          <li><a href="{$site_host}#participa" class="page-scroll">{t}Participa{/t}</a></li>
          <li><a href="{$site_host}#recomendamos" class="page-scroll">{t}Recomendamos{/t}</a></li>
          <li><a href="{$site_host}#" class="page-scroll">{t}Acerca de{/t}</a></li>
        {/if}

        <li class="dropdown langSelector">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{$GLOBAL_C_LANG}<span class="caret"></span></a>
          <ul class="dropdown-menu">
            {foreach key=k item=lang from=$GLOBAL_LANG_AVAILABLE}
              {if $GLOBAL_C_LANG!=$k}<li><a href="{$site_host}/{$k}" class="page-scroll">{$k}</a></li>{/if}
            {/foreach}
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>
