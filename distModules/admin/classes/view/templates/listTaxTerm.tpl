{extends file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/masterAdmin.tpl"}
{block name="masterContent"}

  <script>
    var idTax = {$taxId};
  </script>

  <div class="row">
    <div class="col-lg-4">

      <div class="panel panel-default">
        <div class="panel-body">
          <!--<div class="rolesTable">{$taxTermTable}<div>-->
          <div>

            <ul class="list-group">
              <button id="newTaxTermShow" type="button" class="btn btn-default"> <i class="fa fa-plus"></i>Añadir Term</button>
              <div class="newTaxTermContainer" style="display:none;">
                <input id="newTaxTermName" type="text">
                <button id="newTaxTermSave" type="button" class="btn btn-default">Guardar</button>
              </div>


              {foreach from=$taxTerms item=taxterm}
              <li class="list-group-item">
                <i class="fa fa-tag"></i>{$taxterm->getter('idName')}
                <a class="btn btn-default btn-success" href="/admin/taxonomygroup/{$taxId}/term/edit/{$taxterm->getter('id')}" role="button"><i class="fa fa-pencil"></i></a>
                <a class="btn btn-default btn-danger" href="/admin/taxonomygroup/{$taxId}/term/delete/{$taxterm->getter('id')}" role="button"><i class="fa fa-close"></i></a>
              </li>
              {/foreach}

            </ul>
          </div>

        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
  </div> <!-- end row -->

{/block}


<!--href="/admin/taxonomygroup/{$taxId}/term/create"-->
<!--/admin/taxonomygroup/{$taxId}/term/edit/{$taxterm->getter('id')}-->
