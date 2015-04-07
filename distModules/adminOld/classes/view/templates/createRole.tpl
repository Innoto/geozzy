{extends file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/masterAdmin.tpl"}
{block name="masterContent"}


<div>
  <div class="row">
    <div class="col-lg-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <h4 class="nickname">
              <i class="fa fa-unlock-alt fa-fw"></i>
              New Role
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12" >
              {$createRoleHtml}
            </div>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
  </div> <!-- end row -->
</div>


{/block}