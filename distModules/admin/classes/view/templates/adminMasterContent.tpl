
<div class="headSection clearfix">
  <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
  </div>
  <div class="row">
    <div class="headerTitleContainer col-md-8">
      {block name="headTitle"}{/block}
    </div>
    <div class="headerActionsContainer col-md-4">
      {block name="headActions"}{/block}
    </div>
  </div>


  <!-- /.navbar-header -->


</div><!-- /headSection -->


<div class="contentSection clearfix">
{block name="contentSection"}{/block}
</div><!-- /contentSection -->


<div class="footerSection clearfix">
  <div class="headerActionsContainer">
    {block name="footerActions"}{/block}
  </div>
</div><!-- /footerSection -->
