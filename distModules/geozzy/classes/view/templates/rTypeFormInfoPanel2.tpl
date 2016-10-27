{extends file="admin///adminPanel.tpl"}


{block name="content"}

<div class="infoBasic">
  <div class="row">
    <div class="infoCol col-md-4">ID</div>
    <div class="infoColData col-md-8">{$res.data.id}</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-4">Tipo</div>
    <div class="infoColData col-md-8">{$res.data.rTypeIdName|default:''}</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-4">Creado</div>
    <div class="infoColData col-md-8">{$timeCreation}</div>
  </div>
  <div class="row">
    <div class="infoCol col-md-4">Actualizado</div>
    <div class="infoColData col-md-8">{if isset($update)}{$update.time}<br>({$update.user}){/if}</div>
  </div>

  {if isset($res.data.topicsName)}
  <div class="row">
    <div class="infoCol col-md-12">Temáticas</div>
  </div>
  {foreach $res.data.topicsName as $i => $dir}
  <div class="row rowWhite"><div class="infoCol col-md-4"></div>
    <div class="infoColData col-md-8">{$dir}</div>
  </div>
  {/foreach}
  {/if}

  {if isset($res.data.starred)}
    <div class="row">
      <div class="infoCol col-md-12">Destacados</div>
    </div>
    {foreach $res.data.starred as $star}
    <div class="row rowWhite"><div class="infoCol col-md-4"></div>
      <div class="infoColData col-md-8">{$star["name_`$cogumelo.publicConf.C_LANG`"]}</div>
    </div>
    {/foreach}
  {/if}
</div>

{/block}{*/content*}
