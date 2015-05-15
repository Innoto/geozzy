
<div class="panel panel-default">

  <div class="panel-heading">
    <strong>
      <i class="fa {block name='icono'}{$icon|default:''}{/block} fa-fw"></i>
      {block name="title"}{$title|default:''}{/block}
    </strong>
  </div>

  <div class="panel-body">
    <div class="row">
      <div class="col-lg-12" >
        {block name="content"}{$content}{/block}
      </div>
    </div>
  </div> <!-- end panel-body -->
</div> <!-- end panel -->
