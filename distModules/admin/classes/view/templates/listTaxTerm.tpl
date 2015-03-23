{extends file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/masterAdmin.tpl"}
{block name="masterContent"}

  <div class="row">
    <div class="col-lg-4">

      <div class="panel panel-default">
        <div class="panel-body">
          <!--<div class="rolesTable">{$taxTermTable}<div>-->
          <div>

            <ul class="list-group">
              <a class="btn btn-default" href="/admin/taxonomygroup/{$taxId}/term/create" role="button"><i class="fa fa-plus"></i> Add</a>
              {foreach from=$taxTerms item=taxterm}
              <li class="list-group-item"><i class="fa fa-tag"></i>{$taxterm->getter('idName')}</li>
              {/foreach}
            </ul>
          </div>

        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
  </div> <!-- end row -->

{/block}
