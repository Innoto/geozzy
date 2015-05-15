{extends file="adminMasterContent.tpl"}

{block name="headSection"}
Esta é a miña cabeceira...
{/block}

{block name="contentSection"}

<div class="admin-cols-8-4">
  <div class="row">

    <div class="col-lg-8">

      {if !isset($col8)}{assign var='col8' value=''}{/if}
      {$col8}


    </div> <!-- end col-lg-8 -->

    <div class="col-lg-4">
      {if !isset($col4)}{assign var='col4' value=''}{/if}
      {$col4}

    </div> <!-- end col-lg-4 -->

  </div> <!-- end row -->
</div>

{/block}{*/contentSection*}
