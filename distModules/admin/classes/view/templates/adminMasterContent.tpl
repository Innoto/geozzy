
<div class="headSection">
  <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
  </div>
  <div class="headerTitleContainer">
    {block name="headTitle"}{/block}
  </div>
  <div class="headerActionsContainer">
    {block name="headActions"}{/block}
  </div>

  <!-- /.navbar-header -->


</div><!-- /headSection -->


<div class="contentSection">
{block name="contentSection"}{/block}
</div><!-- /contentSection -->


<div class="footerSection">
  <div class="headerActionsContainer">
    {block name="footerActions"}{/block}
  </div>
</div><!-- /footerSection -->
