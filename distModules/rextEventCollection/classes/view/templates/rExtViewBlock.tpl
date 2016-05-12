<section class="eventCollectionSec gzSection">
  COLECCION DE EVENTOS:

  <!--cargo el tpl según la taxonomía seleccionada en admin-->
  {foreach $rExt.data.rextEventCollectionView as $taxTerm}
    {$viewTpl = "rExt{$taxTerm.idName}Block.tpl"}
    {include $viewTpl}
  {/foreach}
</section>
