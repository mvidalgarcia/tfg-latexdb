<?php
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");
require_once("./mappers/pregunta_mapper.php");

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

		$MapperPregunta =  new PreguntaMapper; 
		$Preguntas = $MapperPregunta->FindByIdProblema($id);
    }
    
    $respuesta["datos"] = $Problema;
    $respuesta["tags"]  = $Tags;
    $respuesta["preguntas"]  = $Preguntas;    
    //
	//RETURN
        echo(json_encode($respuesta));
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
