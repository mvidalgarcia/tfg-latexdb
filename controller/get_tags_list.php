<?php
require_once("../mappers/tags_mapper.php");

try 
{
    // Obtener de BD todos los tags
    $tag = new TagMapper();
    $tags = $tag->FindTagsList();

	//RETURN
    header('Content-type: application/json');
    echo(json_encode($tags,JSON_NUMERIC_CHECK)); 
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
