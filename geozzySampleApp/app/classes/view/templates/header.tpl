<nav role="navigation" class="geozzyHeader navbar navbar-default navbar-fixed-top clearfix">
  <div class="container-fluid">

    <div class="navbar-header">
      <button data-target=".navbar-collapse" data-toggle="collapse" class="navbar-toggle" type="button">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <!-- LOGO in brand section -->
      <div class="logo">
        <a href="/" class="navbar-brand page-scroll">
          <img alt="logo" class="logo img-responsive" src="{$cogumelo.publicConf.media}/img/logo.png"/>
        </a>
      </div>
    </div>

    <div class="collapse navbar-collapse">
      <ul class="nav navbar-nav">
        {if isset($res.data) && ($res.data["urlAlias"] == "/{$cogumelo.publicConf.C_LANG}/" || $res.data["urlAlias"] == "/")}
          <li data-section="faq"><a href="#faq" class="page-scroll">FAQs</a></li>
          <li data-section="contacto"><a href="#contacto" class="page-scroll">CONTACTO</a></li>
          <li class="rs facebook visible-xs"><a href="https://www.facebook.com/GeozzySampleApp?fref=ts" target="_blank"> <i class="fa fa-fx fa-facebook" aria-hidden="true"></i> </a></li>
          <li class="rs twitter visible-xs"><a href="https://twitter.com/GeozzySampleApp" target="_blank"> <i class="fa fa-fx fa-twitter" aria-hidden="true"></i> </a></li>
        {else}
          <li data-section="faq"><a href="/{$cogumelo.publicConf.C_LANG}/#faq" class="page-scroll">FAQs</a></li>
          <li data-section="contacto"><a href="/{$cogumelo.publicConf.C_LANG}/#contacto" class="page-scroll">CONTACTO</a></li>
          <li class="rs facebook visible-xs"><a href="https://www.facebook.com/GeozzySampleApp?fref=ts" target="_blank"> <i class="fa fa-fx fa-facebook" aria-hidden="true"></i> </a></li>
          <li class="rs twitter visible-xs"><a href="https://twitter.com/GeozzySampleApp" target="_blank"> <i class="fa fa-fx fa-twitter" aria-hidden="true"></i> </a></li>
        {/if}
      </ul>
      <div class="redesSociales hidden-xs">
        <a class="fb" href="https://www.facebook.com/GeozzySampleApp?fref=ts" target="_blank"> <i class="fa fa-fx fa-facebook" aria-hidden="true"></i> </a>
        <a class="twitter" href="https://twitter.com/GeozzySampleApp" target="_blank"> <i class="fa fa-fx fa-twitter" aria-hidden="true"></i> </a>
      </div>
    </div>
  </div>
</nav>
