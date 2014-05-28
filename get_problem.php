<?php
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");

try 
{
	if (isset($_GET["id_problema"])) 
    {
        $id = $_GET["id_problema"];
    	
		// Guardar problema buscadolo por su ID
        $MapperProblema = new ProblemaMapper;
        $Problema = $MapperProblema->FindById($id);
		
		// Guardar todos los tags asociados al id del problema
		$MapperProblemaTag = new Problema_TagMapper;
		$Tags = $MapperProblemaTag->FindNameTagsByIdProblema($id);

    }
	
	//RETURN
        echo(json_encode($Problema)); 
        echo(json_encode($Tags)); 
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
