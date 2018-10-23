
<div class="headSection clearfix">
  {block name="headSection"}

  <div class="row">
    <div class="col-12 col-lg-6 clearfix">
      <button type="button" class="navbar-gzz-toggle">
          <span class="sr-only">Toggle navigation</span>
          <i class="fa fa-bars"></i>
      </button>
      <div class="headerTitleContainer">
        <h3>
        {block name="headTitle"}
          {if !isset($headTitle)}{assign var='headTitle' value=''}{/if}
          {$headTitle}
        {/block}
        </h3>

      </div>
    </div>
    <div class="col-12 col-lg-6 clearfix">
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

<div class="contentSection clearfix{if !empty($res.labels)} labelsInfo {' '|implode:$res.labels}{/if}">
  {block name="contentSection"}{/block}
</div><!-- /contentSection -->


<div class="footerSection clearfix">
  {block name="footerSection"}
  <div class="footerActionsContainer">
    {block name="footerActions"}
      {if !isset($footerActions)}{assign var='footerActions' value=''}{/if}
      {$footerActions}
    {/block}
  </div>
  {/block}
</div><!-- /footerSection -->
