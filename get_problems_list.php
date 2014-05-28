<?php
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");
require_once("./mappers/pregunta_mapper.php");

try 
{
	if (isset($_GET["id_problema"])) 
    {
        $id = $_GET["id_problema"];
    	
		// Guardar las preguntas asociadas al problema.
		$MapperPregunta =  new PreguntaMapper; 
		$Preguntas = $MapperPregunta->FindByIdProblema($id);

    }
	
	//RETURN
    echo "<pre>";
        var_dump(json_encode(utf8ize($Preguntas))); 
    echo "</pre>";
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

// Función para pasar a UTF-8 todos los caracteres de un array.
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else {
        return utf8_encode($d);
    }
    return $d;
}
?>
