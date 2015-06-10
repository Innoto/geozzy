{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Stadistic'}{/if}
{/block}

{block name="content"}
  <h3>MultiList Horizontal</h3>

  <select id="asigned_terms2" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <optgroup label="OptG">
      <option data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
    </optgroup>
  {/foreach}
  </select>

  <h3>MultiList Vertical</h3>
  <select id="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
    <option value="99">a99</option>
    <option value="99">a99</option>
  </select>

{literal}

<script type="text/javascript">


$( document ).ready(function() {
/*
  $('#asigned_terms2').multiList();
  $('#asigned_terms3').multiList({
    orientation: 'horizontal'
  });
*/
  $('select').multiMultiList();

});


</script>
{/literal}
{/block}
