<?php
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");
require_once("./mappers/pregunta_mapper.php");

try 
{
    // Obtener de BD todos los 'problemas'
    $Mapper = new ProblemaMapper;
    $problemas = $Mapper->FindAll();
	
	// Obtener de BD relaciones de problemas-tags
	$MapperProblemaTag = new Problema_TagMapper;
	
	// Crear una estructura de datos (array multidimensional) que contenga 
	// los resúmenes de cada problema y sus tags correspondientes.
	for ($i = 0; $i < count($problemas); $i++)
	{
		// Extraer el id del problema
		$id_problema = $problemas[$i]['id_problema'];
		// Guardar todos los tags asociados al id del problema
        $tags_problema = $MapperProblemaTag->FindNameTagsByIdProblema($id_problema);
        $arrayTags = array();
        for ($j =0; $j < count($tags_problema); $j++)
            $arrayTags[$j] = $tags_problema[$j]['nombre'];
		// Introducir en un array multidimensional el id_problema, resumen y los tags asociandolos por el índice 'i'
		$arrayProblemasTags[$i]['id_problema'] = $id_problema;
		$arrayProblemasTags[$i]['resumen'] = $problemas[$i]['resumen'];
		$arrayProblemasTags[$i]['tags'] = $arrayTags;
    }
	//RETURN
    header('Content-type: application/json');
    echo(json_encode($arrayProblemasTags)); 
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
