<?php
// Es un ejemplo con datos "fijos" en lugar
// de sacarlos del modelo, vía algún mapper
require_once("./Twig/lib/Twig/Autoloader.php");
require_once("./mappers/doc_final_mapper.php");

// Cargar la vista
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader);
$template_examen = $twig->loadTemplate('generar_tex/template-examen.tex');

try 
{
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$postdata = file_get_contents("php://input");
		$info = json_decode($postdata);
		$id_doc = $info->id_doc;
    	
		// Crear el mapper para obtener todos los datos necesarios del documento.
		$DocFinalMapper = new DocFinalMapper;
		$fulldoc = $DocFinalMapper->GetTeXInfoDoc($id_doc);
		
		// Cambiar el formato de la fecha de YYYY-mm-dd a dd-mm-YYYY.
		$PhpDate = strtotime($fulldoc->fecha);
		$fulldoc->fecha = date('d-m-Y', $PhpDate );
		
	
		echo "<pre>";
		var_dump($fulldoc);
		echo "</pre>";
		

/*
		// Relleno los datos de un examen de ejemplo
	    // Esto debería salir de la base de datos, dado el id_documento
    	// y otros parámetros que puedan recibirse por POST
	    $examenEjemplo = array(
        	"con_respuestas" => $info->con_soluciones,
       		"con_explicaciones" => $info->con_explicaciones,
        	"asignatura" => "Sistemas Distribuidos",
        	"convocatoria" => "Extraordinaria de Enero",
        	"fecha" => "17/01/2014",
        	"instrucciones" => "Todas las preguntas valen igual, bla bla..."
    	);
    	$examenEjemplo["problemas"] = array();
   		$examenEjemplo["problemas"][0] = array("filename" => "foo-bar-4-1237");
    	$examenEjemplo["problemas"][1] = array("filename" => "foo-bar-3-6381");
    	$examenEjemplo["problemas"][2] = array("filename" => "bar-foo-3-3284");
   		$examenEjemplo["problemas"][3] = array("filename" => "fro-bra-4-0128");
    	$examenEjemplo["problemas"][4] = array("filename" => "faa-bor-1-3634");

    	// Mostrar el examen, en realidad debería escribirse en disco, en 
    	// una carpeta temporal (a investigar cómo)
    	$examen = $template_examen->render($examenEjemplo);
		$f = fopen("file.tex", "w");
		fwrite($f, $examen);
		fclose($f);
*/
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
