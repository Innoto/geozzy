<?php

global $GEOZZY_TAXONOMIESGROUPS;

/**
NEW TOPIC
*/
$GEOZZY_TAXGROUPS['PaisaxesEspectaculares'] = array(
  'name' => array(
    'es' => 'Paisajes espectaculares',
    'en' => 'Spectacular scenery',
    'gl' => 'Paisaxes espectaculares'
  ),
  'resourceTypes' => array(
    array(
      'resourceTypeIdName' => "rtypeRuta",
      'form' => array(
        'viewHtml' => 'modules/rtypeRuta/classes/view/example::viewHtml',
        'update' => 'modules/rtypeRuta/classes/view/example::update',
        'create' => 'modules/rtypeRuta/classes/view/example::create'
      ),
      'weight' => 1
    ),
    array(
      'resourceTypeIdName' => "rtypeEspazoNatural",
      'form' => array(
        'viewHtml' => 'modules/rtypeEspazoNatural/classes/view/example::viewHtml',
        'update' => 'modules/rtypeEspazoNatural/classes/view/example::update',
        'create' => 'modules/rtypeEspazoNatural/classes/view/example::create'
      ),
      'weight' => 3
    )
  ),
  'table' => array(
    'actions' => array(
      'viewHtml' => 'auto',
      'action' => 'auto'
    ),
  ),
  'taxGroupId' => 10,
  'icon' => ''
);
