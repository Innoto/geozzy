
<script type="text/template" id="taxTermEditor">

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
          <!--<div class="rolesTable"> <div>-->
          <div>
              <input class="newTaxTermName" type="text">
              <button type="button" class="newTaxTerm btn btn-default"> <i class="fa fa-plus"></i>Añadir Term</button>

          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->
    </div> <!-- end col -->

  </div> <!-- end row -->
</script>


<script type="text/template" id="taxTermEditorItem">

	<li class="list-group-item" termId="<% id %>">
	  <div class="row">
	    <div class="col-md-9">
	      <div class="infoTerm"><% name %></div>
	      <div class="editTermContainer">
	          <input type="text" class="editTermInput" value="<% name %>" />
	      </div>
	    </div>
	    <div class="col-md-3">
	      <button class="btnSaveTerm btn btn-default btn-success"><i class="fa fa-check"></i></button>
	      <button class="btnEditTerm btn btn-default btn-info"><i class="fa fa-pencil"></i></button>
	      <button class="btnDeleteTerm btn btn-default btn-danger"><i class="fa fa-trash"></i></button>
	      <button class="btnCancelTerm btn btn-default btn-danger"><i class="fa fa-close"></i></button>
	    </div>
	  </div>
	</li>

</script>
