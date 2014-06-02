<?php
require_once("./mappers/problema_mapper.php");

try 
{
	if (isset($_GET["id_problema"])) 
    {
        $id = $_GET["id_problema"];
    	
		// Guardar problema buscadolo por su ID
        $ProblemaMapper = new ProblemaMapper();
        $respuesta = $ProblemaMapper->FindProblemById($id);
    }
  
	//RETURN
	header('Content-type: application/json');
	echo(json_encode($respuesta));
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
