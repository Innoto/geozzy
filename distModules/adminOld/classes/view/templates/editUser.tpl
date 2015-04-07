{extends file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/masterAdmin.tpl"}
{block name="masterContent"}


<div class="gzzAdShowUser">
  <div class="row">
    <div class="col-lg-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <h4 class="nickname">
              <i class="fa fa-user fa-fw"></i>
              Edit user
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12" >
              {$editUserHtml}
            </div>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
    <div class="col-lg-3">

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <h4 class="nickname">
              <i class="fa fa-key fa-fw"></i>
              Change password
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12" >
              {$changePasswordHtml}
            </div>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
    <div class="col-lg-3">

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <h4 class="nickname">
              <i class="fa fa-unlock-alt"></i>
              Roles
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-12" >
              {$userRolesFormHtml}
            </div>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
  </div> <!-- end row -->
</div>


{/block}