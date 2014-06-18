<?php
require_once("./mappers/problema_mapper.php");

try 
{
    // Obtener de BD todos los 'problemas'
    $ProblemaMapper = new ProblemaMapper;
    $problemas = $ProblemaMapper->FindProblemList();

	//RETURN
    header('Content-type: application/json');
    echo(json_encode($problemas, JSON_NUMERIC_CHECK)); 
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
