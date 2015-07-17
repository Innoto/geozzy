{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Stats'}{/if}
{/block}

{block name="content"}
  <h3>MultiList Vertical</h3>

  <select id="asigned_terms2" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <optgroup label="OptG">
      <option data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
    </optgroup>
  {/foreach}
  </select>

  <h3>MultiList Horizontal</h3>
  <select id="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
    <option selected value="98">a98</option>
    <option selected value="99">a99</option>
  </select>


  <h3>MultiList Vertical Image</h3>
  <select id="asigned_terms4" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option data-image="/cgmlformfilews/10" data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
    <option selected data-image="/cgmlformfilews/10" value="98">a98</option>
    <option selected data-image="/cgmlformfilews/10" value="99">a99</option>
  </select>


  <h3>MultiList Horizontal Image</h3>
  <select id="asigned_terms5" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option data-image="/cgmlformfilews/10" data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
    <option selected data-image="/cgmlformfilews/10" value="98">a98</option>
    <option selected data-image="/cgmlformfilews/10" value="99">a99</option>
  </select>

{literal}

<script type="text/javascript">


$( document ).ready(function() {

  $('#asigned_terms2').multiList();
  $('#asigned_terms3').multiList({
    orientation: 'horizontal'
  });

  $('#asigned_terms4').multiList({
    itemImage: true
  });
  $('#asigned_terms5').multiList({
    orientation: 'horizontal',
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': function(){} }
    ],
    icon: '<i class="fa fa-arrows"></i>',
    itemImage: true
  });

/*
  $('select').multiMultiList();
*/
});


</script>
{/literal}
{/block}
