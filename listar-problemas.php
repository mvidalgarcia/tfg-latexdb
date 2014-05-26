<?php

require_once("./Twig/lib/Twig/Autoloader.php");
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");

// Cargar la vista
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('listar-problemas.phtml');
    
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
		// Introducir en un array multidimensional el id_problema, resumen y los tags asociandolos por el índice 'i'
		$arrayProblemasTags[$i]['id_problema'] = $id_problema;
		$arrayProblemasTags[$i]['resumen'] = $problemas[$i]['resumen'];
		$arrayProblemasTags[$i]['tags'] = $tags_problema;
    }
	// Mostrar la vista con los 'problemas'
    $template->display(array("array_probs_tags" => $arrayProblemasTags));
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
