{extends file="adminMasterContent.tpl"}

{block name="headSection"}
Esta é a miña cabeceira...
{/block}

{block name="contentSection"}

<div class="admin-cols-8-4">
  <div class="row">

    <div class="col-lg-8">

      {$col8|default:''}

    </div> <!-- end col-lg-8 -->

    <div class="col-lg-4">

      {$col4|default:''}

    </div> <!-- end col-lg-4 -->

  </div> <!-- end row -->
</div>

{/block}{*/contentSection*}
