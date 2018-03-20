{extends file="adminPanel.tpl"}

{*
{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-user'}{/if}
  {if !isset($title)}{assign var='title' value='Data User'}{/if}
{/block}
*}

{block "header" append}
  <script type="text/javascript" src="{$cogumelo.publicConf.mediaJs}/module/admin/js/adminUser.js"></script>
{/block}

{block name="content"}
{if $translateType === 'export'}
  <div class="row">
    <form class="filterTranslates" action="/admin/translates/export/{if $exportType==='resource'}resourcesexport{else}collectionsexport{/if}" method="get">
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="cgmMForm-wrap">
          <label class="cgmMForm">Seleccionar data de creación</label>
          <input type="date" class="cgmMForm-field" name="creationDate" aria-required="true">
        </div>
      </div>
      <div class="col-xs-12 col-sm-6 col-md-6">
        <div class="cgmMForm-wrap">
          <label class="cgmMForm">Seleccionar data de actualización</label>
          <input type="date" class="cgmMForm-field" name="updateDate" aria-required="true">
        </div>
      </div>
      {if $exportType === 'resource'}
        <div class="col-xs-12 col-sm-6 col-md-6">
          <div class="cgmMForm-wrap">
            <label class="cgmMForm">Seleccionar tipo de recurso</label>
            <select class="gzzSelect2" name="rtype">
              {foreach from=$rTypeData item=rType}
              <option value="{$rType.id}">{$rType.name}</option>
              {/foreach}
            </select>
          </div>
        </div>
      {/if}
      <div class="col-xs-12 col-sm-6 col-sm-6">
        <div class="cgmMForm-wrap">
          <label class="cgmMForm">Seleccionar idioma desde o cal exportar</label>
          <select class="gzzSelect2" name="lang">
            {foreach from=$cogumelo.publicConf.lang_available item=lang key=idLang}
              <option value="{$idLang}">{$lang.name} - Código ISO: {$idLang}</option>
            {/foreach}
          </select>
        </div>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-12">
        <input type="submit" value="{t}Send{/t}">
      </div>
    </form>
  </div>
{elseif $translateType === 'import'}
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12">
      <h2>Instruccións para importar</h2>
      <div class="rules">
        <ol>
          <li>Facer BACKUP da base de datos (IMPORTANTE)</li>
          <li>Configuración:
            <ol type="a">
              <li>Crear carpeta na raíz do proxecto e poñer no seu interior tantas carpetas como idiomas exista no proxecto. Código de idioma empleado ISO 639-1 (<em>es</em>, <em>gl</em>, etc) </li>
              <li>O nome da carpeta tense que definir no arquivo de configuración: <em>( 'mod:admin:importFolder', 'nomeCarpeta' )</em></li>
              <li>Subir os arquivos a carpeta que corresponda co seu idioma (realizarase por SSH)</li>
              <li>Os arquivos non estará anidados en carpetas (non conterán espazos ou caracteres especiais no nome do arquivo). Formato arquivo JSON</li>
              <li>Valida que o arquivo JSON é correcto. (Por exemplo este <a href="https://jsonlint.com/" title="Validador JSON" target="_blank" rel="noopener noreferrer">validador JSON</a>)</li>
              <li>Seleccionar o idioma ao que se quere importar</li>
            </ol>
          </li>
        </ol>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6">
      <div class="cgmMForm-wrap">
        <label class="cgmMForm">Seleccionar idioma para importar</label>
        <select class="gzzSelect2 langToImport" name="lang">
          <option value="">Selecciona idioma</option>
          {foreach from=$cogumelo.publicConf.lang_available item=lang key=idLang}
            <option value="{$idLang}">{$lang.name} - Código ISO: {$idLang}</option>
          {/foreach}
        </select>
      </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
      <button type="button" class="btn btn-primary btnImport">{t}Send{/t}</button>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12 col-sm-12 col-md-10">
      <h2>Resposta da solicitude</h2>
      <div class="requestResponse"></div>
    </div>
  </div>

  <script>
    $( 'button.btnImport' ).on( 'click', function() {
      $( '.requestResponse' ).html( '<i class="fa fa-refresh fa-spin fa-3x fa-fw" aria-hidden="true"></i><span class="sr-only">Procesando...</span>' );
      $( '.requestResponse' ).load( '/admin/translates/import/filesimport?lang=' + $( "select.langToImport" ).val() );
    } );
  </script>
{/if}
{/block}
