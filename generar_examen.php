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
$template_problema = $twig->loadTemplate('generar_tex/template-problema.tex');
$template_pregunta_sola = $twig->loadTemplate('generar_tex/template-pregunta-sola.tex');

try 
{
	ob_start();
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
		
		//DEBUG
		//echo "<pre>";
		//var_dump($fulldoc);
		//echo "</pre>";


		// Relleno los datos de un examen de ejemplo
	    // Esto debería salir de la base de datos, dado el id_documento
    	// y otros parámetros que puedan recibirse por POST
	    $examenEjemplo = array(
        	"con_respuestas" => $info->con_soluciones,
       		"con_explicaciones" => $info->con_explicaciones,
        	"asignatura" => $fulldoc->asignatura,
        	"convocatoria" => $fulldoc->convocatoria,
        	"fecha" => $fulldoc->fecha,
        	"instrucciones" => $fulldoc->instrucciones
    	);
    	$examenEjemplo["problemas"] = array();
   		$examenEjemplo["problemas"][0] = array("filename" => "foo-bar-4-1237");
    	$examenEjemplo["problemas"][1] = array("filename" => "foo-bar-3-6381");
    	$examenEjemplo["problemas"][2] = array("filename" => "bar-foo-3-3284");
   		$examenEjemplo["problemas"][3] = array("filename" => "fro-bra-4-0128");
    	$examenEjemplo["problemas"][4] = array("filename" => "faa-bor-1-3634");

    	// Pasar los datos al template.
		$examen = $template_examen->render($examenEjemplo);

		// Nombre del fichero del estilo Asignatura-Fecha
		$nombre_examen = $fulldoc->asignatura . "-" . $fulldoc->fecha;
		$nombre_examen_tex = $nombre_examen . ".tex";
		
		// Escribir fichero 'maestro' en disco.
		$f = fopen("/tmp/" . $nombre_examen_tex, "w"); //Documento 'maestro'
		fwrite($f, $examen);
		fclose($f);
		
		// Crear fichero ZIP
		$zip = new ZipArchive();
		// Nombre del zip del estilo Asignatura-Fecha_Timestamp.zip
		$nombre_examen_zip = $nombre_examen . '_' . date('Ymd') . '.zip';
		if ($zip->open("/tmp/" . $nombre_examen_zip, ZIPARCHIVE::CREATE) == TRUE) {
   			// Añadir fichero "maestro"
			$zip->addFile("/tmp/" . $nombre_examen_tex);
	    	$zip->close();
    		echo 'Zip creado correctamente.';
		} else {
   			echo 'Fallo en la creación del zip.';
		}

		// Provocar la descarga en el navegador.
		sendFileToBrowser("/tmp/" . $nombre_examen_zip);

		// Borrar ficheros
		//unlink("/tmp/" . $nombre_examen_tex);	
		//unlink("/tmp/" . $nombre_examen_zip);	
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}


// Función para enviar el fichero al navegador para que se inicie la descarga del zip.
function sendFileToBrowser($filepath) {
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
	header('Content-Length: ' . filesize($filepath));
	ob_flush();
	ob_clean(); 
	readfile($filepath);
}
?>
