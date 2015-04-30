
<script type="text/template" id="taxTermEditor">

  <div class="row">
    <div class="col-lg-4">

      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <i class="fa fa-tag fa-fw"></i>
            Listado de terminos para ( <%- name %> )
          </strong>
        </div>
        <div class="panel-body">
          <div id="taxTermListContainer" class="dd">
            <ul class="listTerms dd-list" class="list-group">
            </ul>
          </div>

        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->

    <div class="col-lg-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <i class="fa fa-tag fa-fw"></i>
            Añadir  <%- name %>
          </strong>
        </div>
        <div class="panel-body">
          <!--<div class="rolesTable"> <div>-->
          <div>
              <input class="newTaxTermName" type="text">
              <button type="button" class="newTaxTerm btn btn-default"> <i class="fa fa-plus"></i>Añadir <%- name %></button>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->
    </div> <!-- end col -->

  </div> <!-- end row -->

</script>


<script type="text/template" id="taxTermEditorItem">

  	<li class="dd-item" termId="<%- term.id %>" data-id="<%- term.id %>">
  	  <div class="row dd-handle">
        <ul class="dd-list"></ul>
        
        <div class="rowShow">
    	      <div class="infoTerm"><%- term.name %></div>
            <div class="taxTermActions">
    	        <button class="btnEditTerm btn btn-default btn-info" termId="<%- term.id %>" ><i class="fa fa-pencil"></i></button>
    	        <button class="btnDeleteTerm btn btn-default btn-danger" termId="<%- term.id %>" ><i class="fa fa-trash"></i></button>
    	      </div>
        </div>

        <div class="rowEdit" style="display:none;">
          <div class="editTermContainer">
              <input type="text" class="editTermInput" value="<%- term.name %>" />
          </div>
          <div class="taxTermActions">
            <button class="btnSaveTerm btn btn-default btn-success" termId="<%- term.id %>"><i class="fa fa-check"></i></button>
            <button class="btnCancelTerm btn btn-default btn-danger" termId="<%- term.id %>"><i class="fa fa-close"></i></button>
          </div>
        </div>

  	  </div>
  	</li>

</script>
