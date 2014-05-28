<?php
require_once("./Twig/lib/Twig/Autoloader.php");
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");
require_once("./mappers/pregunta_mapper.php");

// Carga la vista
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('edit.phtml');

try 
{
    // Si se proviene de darle al enlace Ver/Editar del listado de ejercicios
	if (isset($_GET["id"])) 
    {
        $id = $_GET["id"];
    	
		// Guardar problema buscadolo por su ID
        $MapperProblema = new ProblemaMapper;
        $Problema = $MapperProblema->FindById($id);
		
		// Guardar todos los tags asociados al id del problema
		$MapperProblemaTag = new Problema_TagMapper;
		$Tags = $MapperProblemaTag->FindNameTagsByIdProblema($id);

		// Guardar el número de preguntas asociados al problema para imprimir el número de formularios correspondiente
		$MapperPregunta =  new PreguntaMapper; 
		$Preguntas = $MapperPregunta->FindByIdProblema($id);
    }
	
	// Si se proviene de darle al botón del menú de navegación 'Crear ejercicio'
    else
	{
        $Problema = new Problema;
	}

	//DEBUG
	// var_dump(count($Preguntas));
    // echo "<pre>";
    //    var_dump($Preguntas);  //    NO SOY CAPAZ DE LEERLO DESDE JAVASCRIPT PARA RELLENAR EL FORMULARIO DE PREGUNTAS PARA RELLENAR EL FORMULARIO DE PREGUNTAS  
    // echo "</pre>";
	// var_dump($Preguntas[0]['id_pregunta']);
		

    $template->display(array("problema" => $Problema, "tags" => $Tags, "preguntas" => $Preguntas, "npreguntas" => count($Preguntas)));

}
catch(PDOException $e)
{
    echo $e->getMessage();
}
?>
