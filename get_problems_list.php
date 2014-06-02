<?php
require_once("./mappers/problema_mapper.php");

try 
{
    // Obtener de BD todos los 'problemas'
    $ProblemaMapper = new ProblemaMapper;
    $problemas = $ProblemaMapper->FindProblemList();

	//RETURN
    header('Content-type: application/json');
    echo(json_encode($problemas)); 
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
