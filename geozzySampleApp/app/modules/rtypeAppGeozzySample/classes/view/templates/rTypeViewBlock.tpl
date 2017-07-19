{assign scope="global" var="bodySection" value="resourceBlog"}

{block name="headCssIncludes" append}
<style type="text/css">
  .resImageHeader{
    background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/fast/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    height: 65vh;
    width: 100%;
  }
  @media screen and (min-width: 1200px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceLg/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*1200px*/

  @media screen and (max-width: 1199px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceMd/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*1199px*/

  @media screen and (max-width: 991px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceSm/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*991px*/

  @media screen and (max-width: 767px) {
    .resImageHeader{
      background: rgba(0, 0, 0, 0) url("{$cogumelo.publicConf.mediaHost}cgmlImg/{$res.data.image.id}-a{$res.data.image.aKey}/resourceXs/{$res.data.image.name}") no-repeat scroll center center / cover !important;
    }
  }/*767px*/
</style>
{/block}

<!-- rTypeViewBlock.tpl en rtypeAppGeozzySample module -->
<div class="resource resViewBlock {$res.data.rTypeIdName} res_{$res.data.id}" data-resource="{$res.data.id}">

  {if isset($htmlMsg)}<div class="htmlMsg">{$htmlMsg}</div>{/if}

  <section class="gzzSec resImageHeader"></section>

</div><!-- /.resource -->
<!-- /rTypeViewBlock.tpl en rtypeAppGeozzySample module -->
