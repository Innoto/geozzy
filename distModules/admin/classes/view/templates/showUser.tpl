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
              {$user->getter('login')}
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-lg-8" >

              <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label class="col-sm-4 control-label">Name:</label>
                  <div class="col-sm-8">
                    <p class="form-control-static">{$user->getter('name')} {$user->getter('surname')}</p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">Email:</label>
                  <div class="col-sm-8">
                    <p class="form-control-static">{$user->getter('email')}</p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">Role:</label>
                  <div class="col-sm-8">
                    <p class="form-control-static">{$user->getter('role')}</p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">Description:</label>
                  <div class="col-sm-8">
                    <p class="form-control-static">{$user->getter('description')} </p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">Date created:</label>
                  <div class="col-sm-8">
                    <p class="form-control-static">{$user->getter('timeCreateUser')} </p>
                  </div>
                </div>

                <div class="form-group">
                  <label class="col-sm-4 control-label">Last login:</label>
                  <div class="col-sm-8">
                    <p class="form-control-static">{$user->getter('timeLastLogin')} </p>
                  </div>
                </div>
              </form>
            </div>
            <div class="col-lg-4">
              <img src="{$user->getter('avatar')->getter('absLocation')}">
              <a href="/admin/user/edit" class="pull-right btn btn-success"><i class="fa-edit fa fa-fw"></i>Edit Profile</a>
            </div>
          </div>


        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
  </div> <!-- end row -->
</div>

{/block}
