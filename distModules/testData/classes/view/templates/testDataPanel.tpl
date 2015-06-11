
<div class="panel panel-default">

{block "header"}
  {if !isset($icon)}{assign var='icon' value=''}{/if}
  {if !isset($title)}{assign var='title' value=''}{/if}


  {if $icon ne '' or $title ne ''}
  <div class="panel-heading">
    <strong><i class="fa {$icon} fa-fw"></i>{$title}</strong>
  </div>
  {/if}
{/block}

  <div class="panel-body">
    <div class="row">
      <div class="col-lg-12" >
        {block name="content"}{$content}{/block}
      </div>
    </div>
  </div> <!-- end panel-body -->

</div> <!-- end panel -->
