<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppZona extends Module {

  public $name = 'rextAppZona';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextAppZonaType' => array(
      'idName' => 'rextAppZonaType',
      'name' => array(
        'en' => 'Region',
        'es' => 'Zona',
        'gl' => 'Zoa'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'baixominoVigo',
          'icon' => 'view/categoryIcons/baixominoVigo.svg',
          'name' => array(
            'en' => 'Baixo Miño - Vigo',
            'es' => 'Baixo Miño - Vigo',
            'gl' => 'Baixo Miño - Vigo'
          )
        ),
        array(
          'idName' => 'terrasDePontevedra',
          'icon' => 'view/categoryIcons/terrasPontevedra.svg',
          'name' => array(
            'en' => 'Terras de Pontevedra',
            'es' => 'Terras de Pontevedra',
            'gl' => 'Terras de Pontevedra'
          )
        ),
        array(
          'idName' => 'arousa',
          'icon' => 'view/categoryIcons/arousa.svg',
          'name' => array(
            'en' => 'Arousa',
            'es' => 'Arousa',
            'gl' => 'Arousa'
          )
        ),
        array(
          'idName' => 'costaDaMorte',
          'icon' => 'view/categoryIcons/costaDaMorte.svg',
          'name' => array(
            'en' => 'Costa da Morte',
            'es' => 'Costa da Morte',
            'gl' => 'Costa da Morte'
          )
        ),
        array(
          'idName' => 'aMarinaLucense',
          'icon' => 'view/categoryIcons/aMarinaLucense.svg',
          'name' => array(
            'en' => 'A mariña lucense',
            'es' => 'A mariña lucense',
            'gl' => 'A mariña lucense'
          )
        ),
        array(
          'idName' => 'ancaresCourel',
          'icon' => 'view/categoryIcons/ancaresCourel.svg',
          'name' => array(
            'en' => 'Ancares - Courel',
            'es' => 'Ancares - Courel',
            'gl' => 'Ancares - Courel'
          )
        ),
        array(
          'idName' => 'manzanedaTrevinca',
          'icon' => 'view/categoryIcons/manzanedaTrevinca.svg',
          'name' => array(
            'en' => 'Manzaneda - Trevinca',
            'es' => 'Manzaneda - Trevinca',
            'gl' => 'Manzaneda - Trevinca'
          )
        ),
        array(
          'idName' => 'ribeiraSacra',
          'icon' => 'view/categoryIcons/ribeiraSacra.svg',
          'name' => array(
            'en' => 'Ribeira Sacra',
            'es' => 'Ribeira Sacra',
            'gl' => 'Ribeira Sacra'
          )
        ),
        array(
          'idName' => 'ribeiraSacra',
          'icon' => 'view/categoryIcons/ribeiraSacra.svg',
          'name' => array(
            'en' => 'Ribeira Sacra',
            'es' => 'Ribeira Sacra',
            'gl' => 'Ribeira Sacra'
          )
        ),
        array(
          'idName' => 'verinViana',
          'icon' => 'view/categoryIcons/verinViana.svg',
          'name' => array(
            'en' => 'Verín - Viana',
            'es' => 'Verín - Viana',
            'gl' => 'Verín - Viana'
          )
        ),
        array(
          'idName' => 'celanovaAlimia',
          'icon' => 'view/categoryIcons/celanovaAlimia.svg',
          'name' => array(
            'en' => 'Celanova - A Limia',
            'es' => 'Celanova - A Limia',
            'gl' => 'Celanova - A Limia'
          )
        ),
        array(
          'idName' => 'terrasDeOurenseAllariz',
          'icon' => 'view/categoryIcons/terrasDeOurenseAllariz.svg',
          'name' => array(
            'en' => 'Terras de Ourense - Allariz',
            'es' => 'Terras de Ourense - Allariz',
            'gl' => 'Terras de Ourense - Allariz'
          )
        ),
        array(
          'idName' => 'oRibeiro',
          'icon' => 'view/categoryIcons/oRibeiro.svg',
          'name' => array(
            'en' => 'O Ribeiro',
            'es' => 'O Ribeiro',
            'gl' => 'O Ribeiro'
          )
        ),
        array(
          'idName' => 'dezaTabeiros',
          'icon' => 'view/categoryIcons/dezaTabeiros.svg',
          'name' => array(
            'en' => 'Deza - Tabeirós',
            'es' => 'Deza - Tabeirós',
            'gl' => 'Deza - Tabeirós'
          )
        ),
        array(
          'idName' => 'lugoTerraCha',
          'icon' => 'view/categoryIcons/lugoTerraCha.svg',
          'name' => array(
            'en' => 'Lugo - Terra Chá',
            'es' => 'Lugo - Terra Chá',
            'gl' => 'Lugo - Terra Chá'
          )
        ),
        array(
          'idName' => 'terrasDeSantiago',
          'icon' => 'view/categoryIcons/terrasDeSantiago.svg',
          'name' => array(
            'en' => 'Terras de Santiago',
            'es' => 'Terras de Santiago',
            'gl' => 'Terras de Santiago'
          )
        ),
        array(
          'idName' => 'murosNoia',
          'icon' => 'view/categoryIcons/murosNoia.svg',
          'name' => array(
            'en' => 'Muros - Noia',
            'es' => 'Muros - Noia',
            'gl' => 'Muros - Noia'
          )
        ),
        array(
          'idName' => 'ferrolTerra',
          'icon' => 'view/categoryIcons/ferrolTerra.svg',
          'name' => array(
            'en' => 'Ferrol Terra',
            'es' => 'Ferrol Terra',
            'gl' => 'Ferrol Terra'
          )
        ),
        array(
          'idName' => 'aCorunaAsMarinas',
          'icon' => 'view/categoryIcons/aCorunaAsMarinas.svg',
          'name' => array(
            'en' => 'A Coruña - As Mariñas',
            'es' => 'A Coruña - As Mariñas',
            'gl' => 'A Coruña - As Mariñas'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
