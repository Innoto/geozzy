<?php


function cogumeloSetSetupValue( $path, $value ) {
  // error_log( 'COGUMELO::cogumeloSetSetupValue: '.$path );
  global $CGMLCONF;

  if( !isset( $CGMLCONF ) || !is_array( $CGMLCONF ) ) {
    $CGMLCONF = array(
      'cogumelo' => array()
    );
  }

  $parts = explode( ':', $path );
  $stack = '';
  foreach( $parts as $key ) {
    $valid = false;
    $stackPrev = $stack;
    $stack .= '[\''.$key.'\']';
    $fai = '$valid = isset( $CGMLCONF'. $stack .');';
    eval( $fai );
    if( !$valid ) {
      $fai = '$isArray = is_array( $CGMLCONF'. $stackPrev .');';
      eval( $fai );
      if( $isArray ) {
        $fai = '$CGMLCONF'. $stack .' = null;';
        eval( $fai );
      }
      else {
        $fai = '$CGMLCONF'. $stackPrev .' = array( $key => null );';
        eval( $fai );
      }
    }
  }
  $fai = '$CGMLCONF'. $stack .' = $value;';
  eval( $fai );

  return $CGMLCONF;
}

function cogumeloGetSetupValue( $path ) {
  // error_log( 'COGUMELO::cogumeloGetSetupValue: '.$path );
  global $CGMLCONF;
  $value = null;

  $parts = explode( ':', $path );
  $stack = '[\'' . implode( '\'][\'', $parts ) . '\']';
  $fai = '$valid = isset( $CGMLCONF'. $stack .');';
  eval( $fai );
  if( $valid ) {
    $fai = '$value = $CGMLCONF'. $stack .';';
    eval( $fai );
  }

  return $value;
}

