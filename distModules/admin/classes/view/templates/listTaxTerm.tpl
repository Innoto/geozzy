{extends file="/home/proxectos/geozzy/distModules/admin/classes/view/templates/masterAdmin.tpl"}
{block name="masterContent"}

  <script>
    var idTax = {$taxId};
  </script>

  <div class="row">
    <div class="col-lg-4">

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <h4>
              <i class="fa fa-tag fa-fw"></i>
              Listado de terminos
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <ul id="listTerms" class="list-group">
            {foreach from=$taxTerms item=taxterm}
            <li class="list-group-item">

              <div class="row">
                <div class="col-md-8">{$taxterm->getter('idName')}</div>
                <div class="col-md-4">
                  <button class="btn btn-default btn-info"><i class="fa fa-floppy-o"></i></button>
                  <button class="btn btn-default btn-success"><i class="fa fa-pencil"></i></button>
                  <button class="btn btn-default btn-danger"><i class="fa fa-close"></i></button>
                </div>
              </div>

            </li>
            {/foreach}
          </ul>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
    <div class="col-lg-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <h4>
              <i class="fa fa-tag fa-fw"></i>
              Añadir termino
            </h4>
          </strong>
        </div>
        <div class="panel-body">
          <!--<div class="rolesTable">{$taxTermTable}<div>-->
          <div>
              <button id="newTaxTermShow" type="button" class="btn btn-default"> <i class="fa fa-plus"></i>Añadir Term</button>
              <div class="newTaxTermContainer" style="display:none;">
                <input id="newTaxTermName" type="text">
                <button id="newTaxTermSave" type="button" class="btn btn-default">Guardar</button>
              </div>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->
    </div> <!-- end col -->

  </div> <!-- end row -->

{/block}


<!--href="/admin/taxonomygroup/{$taxId}/term/create"-->
<!--/admin/taxonomygroup/{$taxId}/term/edit/{$taxterm->getter('id')}-->
