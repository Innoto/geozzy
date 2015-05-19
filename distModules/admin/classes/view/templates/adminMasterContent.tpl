
<div class="headSection clearfix">
  {block name="headSection"}

  <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
      </button>
  </div>

  <div class="row">
    <div class="col-md-8 col-sm-6">
      <div class="headerTitleContainer">
        <h2>
        {block name="headTitle"}
          {if !isset($headTitle)}{assign var='headTitle' value=''}{/if}
          {$headTitle}
        {/block}
        </h2>
      </div>
    </div>
    <div class="col-md-4 col-sm-6 clearfix">
      <div class="headerActionsContainer">
        {block name="headActions"}
          {if !isset($headActions)}{assign var='headActions' value=''}{/if}
          {$headActions}
        {/block}
      </div>
    </div>
  </div>
  {/block}


  <!-- /.navbar-header -->


</div><!-- /headSection -->


<div class="contentSection clearfix">
{block name="contentSection"}{/block}
</div><!-- /contentSection -->


<div class="footerSection clearfix">
  {block name="footerSection"}
  <div class="headerActionsContainer">
    {block name="footerActions"}
      {if !isset($footerActions)}{assign var='footerActions' value=''}{/if}
      {$footerActions}
    {/block}
  </div>
  {/block}
</div><!-- /footerSection -->
