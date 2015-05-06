
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
            <ol class="listTerms dd-list">
            </ol>
          </div>
          <button class="btn btn-primary">Save</button>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->

    </div> <!-- end col -->

    <div class="col-lg-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <strong>
            <i class="fa fa-tag fa-fw"></i>
            Añadir a <%- name %>
          </strong>
        </div>
        <div class="panel-body">
          <!--<div class="rolesTable"> <div>-->
          <div>
              <input class="newTaxTermName" type="text">
              <button type="button" class="newTaxTerm btn btn-default"> <i class="fa fa-plus"></i>Añadir a <%- name %></button>
          </div>
        </div> <!-- end panel-body -->
      </div> <!-- end panel -->
    </div> <!-- end col -->
  </div> <!-- end row -->

</script>


<script type="text/template" id="taxTermEditorItem">

  	<li class="dd-item" data-id="<%- term.id %>">
      <div class="dd-item-container clearfix">

        <div class="dd-content">
          <div class="taxTermActions">
  	        <button class="btnEditTerm btn btn-default btn-info" data-id="<%- term.id %>" ><i class="fa fa-pencil"></i></button>
  	        <button class="btnDeleteTerm btn btn-default btn-danger" data-id="<%- term.id %>" ><i class="fa fa-trash"></i></button>
  	      </div>
    	  </div>

        <div class="dd-handle">
          <div class="icon-handle"><i class="fa fa-arrows"></i></div>
          <div class="infoTerm"><%- term.name %></div>          
        </div>

      </div>
    </li>

</script>
