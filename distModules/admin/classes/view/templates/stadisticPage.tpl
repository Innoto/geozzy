{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Stadistic'}{/if}
{/block}

{block name="content"}
  <h3>Chosen with extra</h3>
  <select id="asigned_terms" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
  </select>
  <h3>Select 4.0.0</h3>
  <select id="asigned_terms2" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
  </select>
  <h3>...</h3>
  <select id="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <option value="{$item->getter('id')}">{$item->getter('name')}</option>
  {/foreach}
  </select>
  <h3>Select 3.5.2</h3>
    <input id="e15" type="hidden" style="width:250px;"></input>
{literal}
  <script type="text/javascript">
    $( document ).ready(function() {

      $('#asigned_terms').chosen();
      $('#asigned_terms').addClass('chosen-sortable').chosenSortable();


      $('#asigned_terms2').select2();
      //$('#asigned_terms3').select2();





      $("#asigned_terms3").select2("container").find("ul.select2-choices").sortable({
          containment: 'parent',
          start: function() { $("#asigned_terms3").select2("onSortStart"); },
          update: function() { $("#asigned_terms3").select2("onSortEnd"); }
      });


      $("#e15").select2({tags:["red", "green", "blue", "orange", "white", "black", "purple", "cyan", "teal"]});
      $("#e15").on("change", function() { $("#e15_val").html($("#e15").val());});

      $("#e15").select2("container").find("ul.select2-choices").sortable({
          containment: 'parent',
          start: function() { $("#e15").select2("onSortStart"); },
          update: function() { $("#e15").select2("onSortEnd"); }
      });


    });
  </script>
{/literal}
{/block}
