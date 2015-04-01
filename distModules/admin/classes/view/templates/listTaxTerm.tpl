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
            <li class="list-group-item" termId="{$taxterm->getter('id')}">

              <div class="row">
                <div class="col-md-9">
                  <div class="infoTerm">{$taxterm->getter('idName')}</div>
                  <div class="editTermContainer">
                      <input type="text" class="editTermInput" value="{$taxterm->getter('idName')}" />
                  </div>
                </div>
                <div class="col-md-3" style="background:red;">
                  <button class="btnSaveTerm btn btn-default btn-success"><i class="fa fa-check"></i></button>
                  <button class="btnEditTerm btn btn-default btn-info"><i class="fa fa-pencil"></i></button>
                  <button class="btnDeleteTerm btn btn-default btn-danger"><i class="fa fa-trash"></i></button>
                  <button class="btnCancelTerm btn btn-default btn-danger"><i class="fa fa-close"></i></button>
                </div>
              </div>

            </li>
            {/foreach}
          </ul>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->
    {if $taxEditable}
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
    {/if}
  </div> <!-- end row -->

{/block}


<!--href="/admin/taxonomygroup/{$taxId}/term/create"-->
<!--/admin/taxonomygroup/{$taxId}/term/edit/{$taxterm->getter('id')}-->
