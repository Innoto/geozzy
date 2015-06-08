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
      <option data-multilist-id="{$item->getter('id')}" data-multilist-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
    </optgroup>
  {/foreach}
  </select>

  <h3>MultiList Vertical</h3>
  <select id="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
      <optgroup label="OptG">
        <option value="{$item->getter('id')}">{$item->getter('name')}</option>
      </optgroup>
  {/foreach}
  </select>

{literal}

<script type="text/javascript">


$( document ).ready(function() {

  $('#asigned_terms2, #asigned_terms3').multiList();
  /*$('#asigned_terms3').multiList({
    orientation: 'horizontal'
  });*/




/*
  Select 3.5.2 (SELECT) (Select2.sortable)
*/
/*
      $('#asigned_terms3').select2Sortable({
        bindOrder: 'sortableStop',
      });
*/
/*
  Select 3.5.2 (INPUT)
*/
      /*
      $("#e15").on("change", function() {
          $("#e15_val").html( $("#e15").val() );
      });
      */
/*
      $("#e15").select2({
        tags :[{
          text: "Balon",
          children:[
            { id: "10", "text": "Futbol"},
            { id: "11", "text": "Tenis"},
            { id: "12", "text": "Baloncesto"}
          ]
        },
        {
          text: "Motor",
          children:[
            { id: "13", "text": "F1"},
            { id: "14", "text": "Motos"},
            { id: "15", "text": "Rally"}
          ]
        }]
      });

      $("#e15").select2("container").find("ul.select2-choices").sortable({
          containment: 'parent',
          start: function() { $("#e15").select2("onSortStart"); },
          update: function() { $("#e15").select2("onSortEnd"); }
      });
*/
});


</script>
{/literal}
{/block}
