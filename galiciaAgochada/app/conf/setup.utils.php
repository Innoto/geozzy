<?php


function setCogumeloSetupConf( $path, $value ) {
  // error_log( 'COGUMELO::setCogumeloSetupConf: '.$path );
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

function getCogumeloSetupConf( $path ) {
  // error_log( 'COGUMELO::getCogumeloSetupConf: '.$path );
  global $CGMLCONF;
  $value = null;

  $parts = explode( ':', $path );
  $stack = '[\'' . implode( '\'][\'', $parts ) . '\']';
  echo $stack;
  $fai = '$valid = isset( $CGMLCONF'. $stack .');';
  eval( $fai );
  if( $valid ) {
    $fai = '$value = $CGMLCONF'. $stack .';';
    eval( $fai );
  }

  return $value;
}

