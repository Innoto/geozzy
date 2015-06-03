{extends file="adminPanel.tpl"}

{block "header" prepend}
  {if !isset($icon)}{assign var='icon' value='fa-unlock-alt'}{/if}
  {if !isset($title)}{assign var='title' value='Stadistic'}{/if}
{/block}

{block name="content"}
  <h3>Select 4.0.0</h3>

  <div class="asign2selected dd">
    <ol class="dd-list">
        <li class="dd-item" data-id="1">
            <div class="dd-handle">Item 1</div>
        </li>
        <li class="dd-item" data-id="2">
            <div class="dd-handle">Item 2</div>
        </li>
        <li class="dd-item" data-id="3">
            <div class="dd-handle">Item 3</div>
        </li>
    </ol>
  </div>

  <select id="asigned_terms2" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
    <optgroup label="OptG">
      <option data-multilist-id="{$item->getter('id')}" data-multilist-order="{$item->getter('weight')}" value="{$item->getter('id')}">{$item->getter('name')}</option>
    </optgroup>
  {/foreach}
  </select>

  <h3>Select 3.5.2 (Select)</h3>
  <select id="asigned_terms3" multiple style="width:250px;">
  {foreach key=key item=item from=$termArray}
      <optgroup label="OptG">
        <option value="{$item->getter('id')}">{$item->getter('name')}</option>
      </optgroup>
  {/foreach}
  </select>

  <h3>Select 3.5.2 (Input)</h3>
  <input id="e15" type="hidden" style="width:250px;"></input>
{literal}
  <script type="text/javascript">
    $( document ).ready(function() {

var dataSelected = [];
var selector = $('#asigned_terms2);
var selectedOptions = $('#asigned_terms2 option:selected');
var nestableContainer = $('.asign2selected');



selectedOptions.each(function( index ) {
  dataSelected.push({
    id: $( this ).val(),
    value: $( this ).val(),
    weight: $( this ).attr('data-multilist-weight')
  });
});


nestableContainer.nestable({
  maxDepth: 1
});
selector.select2({
  tags: "true",
  placeholder: "Select an option"
});





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
