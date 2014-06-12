<?php

require_once("./mappers/doc_final_mapper.php");
require_once("./mappers/problema_mapper.php");

try 
{
	if (isset($_GET["id_doc"])) 
    {
        $id = $_GET["id_doc"];
    	
		// Guardar documento buscadolo por su ID
        $DocFinalMapper = new DocFinalMapper();
        $doc = $DocFinalMapper->FindDocById($id);
    }
  
	//RETURN
	header('Content-type: application/json');
	echo(json_encode($doc));
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>