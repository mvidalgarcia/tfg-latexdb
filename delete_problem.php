<?php
  $post_data = file_get_contents("php://input");
  $id = json_decode($post_data, true);
  echo($id["id_problema"]);
?>
