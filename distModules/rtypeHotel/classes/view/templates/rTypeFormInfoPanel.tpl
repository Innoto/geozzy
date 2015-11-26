{extends file="admin///adminPanel.tpl"}


{block name="content"}

<div class="infoBasic">'.
  <div class="row">
    <div class="infoCol col-md-4">ID</div>
    <div class="infoColData col-md-8">$resourceId</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-4">Tipo</div>
    <div class="infoColData col-md-8">$resourceType</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-4">Creado</div>
    <div class="infoColData col-md-8">$timeCreation($user)</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-4">Actualizado</div>
    <div class="infoColData col-md-8">$update</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-12">Temáticas</div>
  </div>
  {foreach $resourceTopicList as $topic}
  <div class="row rowWhite"><div class="infoCol col-md-4"></div>'.
    '<div class="infoColData col-md-8">'.$allTopics[ $topicList->getter( 'topic' ) ].'</div>
  </div>
  <div class="row"><div class="infoCol col-md-12">Destacados</div></div>$starredHtml
</div>

{/block}{*/content*}
