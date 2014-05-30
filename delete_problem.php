<?php
  $method = $_SERVER['REQUEST_METHOD'];
  if ($method!="DELETE") {
      header('HTTP/1.1 405 Method Not Allowed');
      echo("Only DELETE method allowed");
      return;
  }
  $id = $_GET["id_problema"];
  echo ("OK. Se supone que he borrado el problema $id");
?>
