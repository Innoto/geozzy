<div class="{$rExtName} formBlock">
  <div class="clearfix">
    <div class="row">
      <div class="col-md-8">
        {$rExt.dataForm.formFieldsArray.rExtComment_activeComment}
      </div>
      <div class="col-md-4">
        <select class="typeComment pull-right">
          {foreach key=key item=ctype from=$commentTypeOptions}
            <option value="{$key}">{$ctype}</option>
          {/foreach}
        </select>
      </div>
      <div class="commentListContainer col-md-12">

      </div>
    </div>
  </div>
</div>

<!-- /rExtFormBlock.tpl en  module -->
