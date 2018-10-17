<div class="card">
  {block "header"}
    {if !isset($icon)}{assign var='icon' value=''}{/if}
    {if !isset($title)}{assign var='title' value=''}{/if}

    {if $icon ne '' or $title ne ''}
      <div id="{$title|replace:' ': '_'}" class="card-header panelBlocktoLink">
        <!--<i class="fa {$icon} fa-fw"></i>-->
        {$title}
      </div>
    {/if}
  {/block}
  <div class="card-body">
    <div class="row">
      <div class="col">
        {block name="content"}{$content}{/block}
      </div>
    </div>
  </div>
</div>
