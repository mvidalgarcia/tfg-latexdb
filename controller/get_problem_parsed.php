<?php
require_once("../mappers/problema_mapper.php");
require_once("../Pandoc/Pandoc.php");

use Pandoc\Pandoc;

try 
{
	if (isset($_GET["id_problema"])) 
    {
        $id = $_GET["id_problema"];
    	
		// Guardar problema buscadolo por su ID
        $ProblemaMapper = new ProblemaMapper();
        $respuesta = $ProblemaMapper->FindProblemById($id);
    }

    // Parsear el enunciado general como markdown
    $pandoc = new Pandoc();
    $respuesta->enunciado_general = $pandoc->convert($respuesta->enunciado_general, "markdown", "html");
  
    // Para cada problema, parsear también:
    foreach ($respuesta->preguntas as $pregunta) {
        $pregunta->enunciado = $pandoc->convert($pregunta->enunciado, "markdown", "html");
        $pregunta->solucion = $pandoc->convert($pregunta->solucion, "markdown", "html");
        $pregunta->explicacion = $pandoc->convert($pregunta->explicacion, "markdown", "html");
    }
	//RETURN
	header('Content-type: application/json');
	echo(json_encode($respuesta, JSON_NUMERIC_CHECK));
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
