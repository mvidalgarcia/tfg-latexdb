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
    if ($respuesta->formato )
        $formato_fuente = $respuesta->formato;
    else 
        $formato_fuente = "markdown";
    $respuesta->enunciado_general = $pandoc->convert($respuesta->enunciado_general, $formato_fuente, "html");
  
    // Para cada problema, parsear también:
    foreach ($respuesta->preguntas as $pregunta) {
        $pregunta->enunciado = $pandoc->convert($pregunta->enunciado, $formato_fuente, "html");
        $pregunta->solucion = $pandoc->convert($pregunta->solucion, $formato_fuente, "html");
        $pregunta->explicacion = $pandoc->convert($pregunta->explicacion, $formato_fuente, "html");
    }
	// Limpiar el buffer de salida antes de enviar.
	ob_clean(); 
	//RETURN
	header('Content-type: application/json');
	echo(json_encode($respuesta, JSON_NUMERIC_CHECK));
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
