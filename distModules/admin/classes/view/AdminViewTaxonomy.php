<?php
admin::load('view/AdminViewMaster.php');


class AdminViewTaxonomy extends AdminViewMaster
{

  function __construct($base_dir){
    parent::__construct($base_dir);
  }


  function categoryTermsSync( $request ) {


    $id = substr($request[1], 1);
    
    header('Content-type: application/json');

    switch( $_SERVER['REQUEST_METHOD'] ) {
      case 'PUT':

        $putData = json_decode(file_get_contents('php://input'), true);

        if( is_numeric( $id )) {  // update
          $taxtermModel = new TaxonomytermModel();
          $taxTerm = $taxtermModel->listItems(  array( 'filters' => array( 'id'=>$id ) ))->fetch();
          $taxTerm->setter('name', $putData['name']);
          $taxTerm->save();
        }
        else { // create
          $taxTerm = new TaxonomytermModel( array('name'=> $putData['name'], 'parent'=> null, 'taxgroup'=> $putData['taxgroup']) );
          $taxTerm->save();

        }      
        echo json_encode( $taxTerm->getAllData()['data'] );

        break;
      case 'GET':
        $taxtermModel = new TaxonomytermModel();
        $taxtermList = $taxtermModel->listItems(  array( 'filters' => array( 'taxgroup'=>$_GET['group']) ) );
        echo '[';
        $c = '';
        while ($taxTerm = $taxtermList->fetch() )
        {
          echo $c.json_encode( $taxTerm->getAllData()['data'] );
          if($c === ''){$c=',';}
        }
        echo ']';     

        break;

      case 'DELETE':
        $taxTerm = new TaxonomytermModel( array( 'id'=> $id ) );
        $taxTerm->delete();

        break;
    }

  

  }

  function categoriesSync() {
    $taxgroupModel = new TaxonomygroupModel();
    $taxGroupList = $taxgroupModel->listItems(array( 'filters' => array( 'editable'=>1 ) ));

    header('Content-type: application/json');

    echo '[';

    $c = '';
    while ($taxGroup = $taxGroupList->fetch() )
    {
      echo $c.json_encode( $taxGroup->getAllData()['data'] );
      if($c === ''){$c=',';}
    }
    echo ']';
  
  }


}

