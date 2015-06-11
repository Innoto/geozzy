<?php

$url = HttpRequest::getUrl();
$num = $_GET['n'];

for ($i=0; i<$num+1; $i++){
  print($i);
}

?>