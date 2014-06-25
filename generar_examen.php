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
        	"asignatura" => $fulldoc->asignatura,
        	"convocatoria" => $fulldoc->convocatoria,
        	"fecha" => $fulldoc->fecha,
        	"instrucciones" => $fulldoc->instrucciones
        );
        $opciones = "examen";
        if ($info->con_soluciones) $opciones .= ",solucion";
        if ($info->con_explicaciones) $opciones .= ",explicacion";

        $examenEjemplo["opciones"] = $opciones;
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


        // Crear nombre para carpeta temporal
        $tmp_folder = tempnam(sys_get_temp_dir(), "TEX");
        // Lo anterior crea un fichero temporal de nombre único, tenemos que borrarlo
        // para crear una carpeta usando ese mismo nombre, que será del tipo /tmp/TEXaAuiq/
        unlink($tmp_folder);
        mkdir($tmp_folder);
        // Cambiar a esa carpeta para trabajar localmente, sin tener que poner rutas absolutas
        chdir($tmp_folder);
		// Escribir fichero 'maestro' en disco.
		$f = fopen($nombre_examen_tex, "w"); //Documento 'maestro'
		fwrite($f, $examen);
		fclose($f);
		
		// Crear fichero ZIP
		$zip = new ZipArchive();
		// Nombre del zip del estilo Asignatura-Fecha_Timestamp.zip
		$nombre_examen_zip = $nombre_examen . '_' . date('Ymd') . '.zip';
		if ($zip->open($nombre_examen_zip, ZIPARCHIVE::CREATE) == TRUE) {
            // Añadir fichero "maestro"
            // El segundo parámetro es el nombre que tendrá el fichero dentro del zip. 
            // Es necesario ponerlo para resolver problemas de encoding, ya que el formato
            // zip no admite nombres de fichero en utf8, sino en CP850 (MS-DOS)
			$zip->addFile($nombre_examen_tex, iconv("utf-8", "cp850", $nombre_examen_tex));
            $zip->close();

            // Subir el zip a la carpeta superior de la temporal
            copy ($nombre_examen_zip, "../" . $nombre_examen_zip);
            // Y borrar la carpeta temporal
            $ficheros = scandir(".");
            foreach ($ficheros as $fichero) unlink($fichero);
            chdir("..");
            rmdir($tmp_folder);

            // Enviar al navegador una respuesta en JSON que incluya
            // una URL de la cual descargar el resultado
            $respuesta = array(
                "status" => "OK",
                "url" => "get_zip.php?name=" . sys_get_temp_dir() . "/" . $nombre_examen_zip
            );
		} else {
            $respuesta = array(
                "status" => "Error en la creación del zip",
                "url" => NULL
            );
		}

        // Enviar la respuesta
        header('Content-type: application/json');
        echo(json_encode($respuesta, JSON_NUMERIC_CHECK));


		// Borrar ficheros
		// unlink("/tmp/" . $nombre_examen_tex);	
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
