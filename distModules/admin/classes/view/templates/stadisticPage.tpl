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


  <script type="text/javascript">
    $( document ).ready(function() {
      alert('hola');
      $('#asigned_terms').select2();
      $('#asigned_terms2').select2();
      $('#asigned_terms3').select2();





      $("#asigned_terms3").select2("container").find("ul.select2-choices").sortable({
          containment: 'parent',
          start: function() { $("#asigned_terms3").select2("onSortStart"); },
          update: function() { $("#asigned_terms3").select2("onSortEnd"); }
      });


  //    $("#e15").on("change", function() { $("#e15_val").html($("#e15").val());});

      $("#e15").select2("container").find("ul.select2-choices").sortable({
          containment: 'parent',
          start: function() { $("#e15").select2("onSortStart"); },
          update: function() { $("#e15").select2("onSortEnd"); }
      });


    });
  </script>
{/block}
