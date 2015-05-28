{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Stadistic'}{/if}
{/block}

{block name="content"}
  <select id="asigned_terms" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
  </select>

  <select id="asigned_terms2" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
  </select>

  <select id="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
  </select>

  <script>
    /*$( document ).ready(function() {
      alert('hola');
      $('#asigned_terms').chosen();

      $('#asigned_terms2').select2();

      $('#asigned_terms3').select2Sortable();
    });*/
  </script>

{/block}
