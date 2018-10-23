{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Stats'}{/if}
{/block}

{block name="content"}

  <h3>MultiList Vertical</h3>
  <select class="asigned_terms2" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <optgroup label="OptG">
      <option data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
    </optgroup>
  {/foreach}
    <option selected value="94">a94</option>
    <option selected value="95">a95</option>
    <option selected value="96">a96</option>
    <option selected value="97">a97</option>
    <option selected value="98">a98</option>
    <option selected value="99">a99</option>
  </select>

  <h3>MultiList Horizontal</h3>
  <select class="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
    <option selected value="94">a94</option>
    <option selected value="95">a95</option>
    <option selected value="96">a96</option>
    <option selected value="97">a97</option>
    <option selected value="98">a98</option>
    <option selected value="99">a99</option>
  </select>


  <h3>MultiList Vertical Image</h3>
  <select id="asigned_terms4" multiple style="width:250px;">
    <option data-order="1" selected data-image="http://lorempixel.com/200/200/sports/2/" value="97">a97</option>
  {foreach key=key item=item from=$termArray}
    <option data-image="http://lorempixel.com/200/200/sports/10/" data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}

    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/1/" value="99">a99</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/3/" value="98">a98</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/4/" value="96">a96</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/5/" value="95">a95</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/6/" value="94">a94</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/7/" value="93">a93</option>

  </select>


  <h3>MultiList Horizontal Image</h3>
  <select id="asigned_terms5" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option data-image="http://lorempixel.com/200/200/sports/10/" data-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/1/" value="99">a99</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/3/" value="98">a98</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/4/" value="96">a96</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/5/" value="95">a95</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/6/" value="94">a94</option>
    <option data-order="0" selected data-image="http://lorempixel.com/200/200/sports/7/" value="93">a93</option>
  </select>

{literal}

<script type="text/javascript">


$( document ).ready(function() {

  $('.asigned_terms2').multiList({
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>'
  });
  $('.asigned_terms3').multiList({
    orientation: 'horizontal',
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>'
  });

  $('#asigned_terms4').multiList({
    itemImage: true
  });
  $('#asigned_terms5').multiList({
    orientation: 'horizontal',
    itemActions : [
      { 'id': 'edit', 'html': '<i class="far fa-edit fa-xs fa-fw"></i>', 'action': function(){} }
    ],
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>',
    itemImage: true
  });

});


</script>
{/literal}
{/block}
