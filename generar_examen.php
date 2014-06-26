<?php
// Es un ejemplo con datos "fijos" en lugar
// de sacarlos del modelo, vía algún mapper
require_once("./Twig/lib/Twig/Autoloader.php");
require_once("./mappers/doc_final_mapper.php");

// Cargar la vista
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader,array('autoescape' => false) );
$template_examen = $twig->loadTemplate('generar_tex/template-examen.tex');
$template_problema = $twig->loadTemplate('generar_tex/template-problema.tex');
$template_pregunta_sola = $twig->loadTemplate('generar_tex/template-pregunta-sola.tex');
$twig_string = new Twig_Environment(new Twig_Loader_String(), array('autoescape' => false) );

try 
{
	ob_start();
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
        setlocale(LC_ALL, "es_ES.UTF-8");
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

		// Relleno los datos comunes del documento(examen)
	    // Se obtiene de la base de datos, dado el id_documento
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
		
		// Crear una nueva carpeta temporal y moverse a su ruta, donde se copiarán los ficheros .tex.
		$tmp_folder = NewTempFolder();

        $puntuacion_total = 0;
        $preguntas_totales = 0;
		// Recorrer todos los problemas del documento para ir metiendo su contenido en variables.
		foreach ($fulldoc->problemas as $problema) {
			// Nombre del tex del problema del tipo tag1-tag2-numpreguntas-id_problema.tex
			// Unir los tags del problema en una sola cadena unidos por guiones.
			$joined_tags = "";
			foreach ($problema->tags as $tag)
				$joined_tags .= $tag->nombre."-";  
			
			// Formar el nombre .tex
			$nombre_problema = $joined_tags . $problema->num_preguntas . "-" . $problema->id_problema;
            $nombre_problema = str_replace(" ", "-", iconv("UTF-8", "ASCII//TRANSLIT", $nombre_problema));
			$nombre_problema_tex = $nombre_problema . ".tex";
			
			// Insertar el nombre del problema en el array del examen (No es necesaria la extensión).
            array_push($examenEjemplo["problemas"], array("filename" => $nombre_problema));

            // Sumar la puntuación del problema a la del examen
            $puntuacion_total += $problema->puntos;
            $preguntas_totales += $problema->num_preguntas;
						
			// Meter los valores del problema en el template correspondiente.
			$problemaTex = InsertProblemInTemplate($problema, $joined_tags, $template_pregunta_sola, $template_problema);
			
			// Escribir fichero templete pregunta-sola/problema en disco.
			$f = fopen($nombre_problema_tex, "w");
			fwrite($f, $problemaTex);
			fclose($f);
		}

        // Procesar las instrucciones como si fueran un template, para permitir
        // que contengan expresiones relativas a los datos del examen. Es decir
        // las instrucciones pueden contener expresiones como {{num_preguntas}} y {{puntos}}
        // para referirse al número de preguntas o puntos totales del examen

        $meta_info = array(
            "num_preguntas" => $preguntas_totales,
            "puntos" => $puntuacion_total);
        $examenEjemplo["instrucciones"] = $twig_string->render($examenEjemplo["instrucciones"], $meta_info);

    	// Pasar los datos del examen completo al template.
		$examen = $template_examen->render($examenEjemplo);

		// Nombre del fichero del estilo Asignatura-Fecha
		$nombre_examen = $fulldoc->asignatura . "-" . $fulldoc->fecha;
        $nombre_examen = str_replace(" ", "-", iconv("utf-8", "ASCII//TRANSLIT", $nombre_examen));
		$nombre_examen_tex = $nombre_examen . ".tex";
    	
		// Escribir fichero 'maestro' en disco.
		$f = fopen($nombre_examen_tex, "w"); //Documento 'maestro'
		fwrite($f, $examen);
		fclose($f);
		
		// Nombre del zip del estilo Asignatura-Fecha_Timestamp.zip
		$nombre_examen_zip = $nombre_examen . '_' . date('Ymd') . '.zip';

		// Crear fichero ZIP y meter todos los ficheros en él. Obtener URL del zip.
		$respuesta = InsertInZipFile($nombre_examen, $tmp_folder, $nombre_examen_zip, $examenEjemplo["problemas"]);

		// Limpiar el buffer de salida antes de enviar.
		ob_clean(); 
        // Enviar la respuesta
        header('Content-type: application/json');
        echo(json_encode($respuesta, JSON_NUMERIC_CHECK));
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}



/************* Funciones auxiliares *************/


// Función que crea un fichero zip con el nombre del examen e introduce en
// él todos los ficheros necesarios para comilar el examen.
// Si todo va bien devuelve un array con la respuesta que se le debe 
// pasar al cliente, donde se encuentra la URL del zip a descargar.
function InsertInZipFile ($nombre_examen, $tmp_folder, $nombre_examen_zip, $name_problemas) {
	$nombre_examen_tex = $nombre_examen . ".tex";
	// Crear fichero ZIP
	$zip = new ZipArchive();
		if ($zip->open($nombre_examen_zip, ZIPARCHIVE::CREATE) == TRUE) {
        // Añadir fichero "maestro"
        // El segundo parámetro es el nombre que tendrá el fichero dentro del zip. 
        // Es necesario ponerlo para resolver problemas de encoding, ya que el formato
        // zip no admite nombres de fichero en utf8, sino en CP850 (MS-DOS)
		$zip->addFile($nombre_examen_tex, iconv("utf-8", "ASCII//TRANSLIT", $nombre_examen_tex));
		// Meter en el zip todos los problemas.
		foreach($name_problemas as $name_prob)
			$zip->addFile($name_prob["filename"].'.tex', iconv("utf-8", "cp850", $name_prob["filename"].'.tex'));
		// Meter en el zip el fichero .sty de estilos.
		$zip->addFile('examen.sty');
		$zip->addFile('fink.sty');
        $zip->close();

        // Subir el zip a la carpeta superior de la temporal
        copy ($nombre_examen_zip, "../" . $nombre_examen_zip) ;
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
	return $respuesta;
}


// Función para crear nombre para carpeta temporal y moverse a ella.
// Retorna la ruta de la carpeta temporal.
function NewTempFolder() {
    $tmp_folder = tempnam(sys_get_temp_dir(), "TEX");
    // Lo anterior crea un fichero temporal de nombre único, tenemos que borrarlo
    // para crear una carpeta usando ese mismo nombre, que será del tipo /tmp/TEXaAuiq/
    unlink($tmp_folder);
    mkdir($tmp_folder);
	// Copiar fichero de estilos a la nueva ruta
	copy ('generar_tex/examen.sty', $tmp_folder.'/examen.sty');
	copy ('generar_tex/fink.sty', $tmp_folder.'/fink.sty');
    // Cambiar a esa carpeta para trabajar localmente, sin tener que poner rutas absolutas
	chdir($tmp_folder);

	return $tmp_folder;
}


// Función que a partir de un problema, obtiene sus datos y los mete en el template de latex
// que le corresponde en funcion de si es una pregunta sola o un problema con varias preguntas.
// Retorna el valor del template rellenado con los campos.
function InsertProblemInTemplate($problema, $joined_tags, $template_pregunta_sola, $template_problema) {
	// Comprobar si se trata de una pregunta-sola o un problema con varias para
	// usar un template u otro.
	// Si no tiene enunciado general y tiene una sola pregunta -> pregunta-sola
	if (empty($problema->enunciado_general) and $problema->num_preguntas == 1) {
		 $preguntaSola = array(
    		"puntos" => $problema->puntos,
	       	"tags" => str_replace('-',' ', $joined_tags),
    		"resumen" => $problema->resumen,
    		"pregunta" => $problema->preguntas[0] // Solo va a haber una, la del primer índice.
        );
		// Pasar los datos al template pregunta-sola y guardar el contenido.
		$problemaTex = $template_pregunta_sola->render($preguntaSola);
	}

	// Si tiene enunciado general o varias preguntas -> problema
	else {
		$problemaNormal = array(
			"tiene_enunciado_general" => ($problema->enunciado_general != ""),
    		"total_puntos" => $problema->puntos,
	       	"tags" => str_replace('-',' ', $joined_tags),
    		"resumen" => $problema->resumen,
    		"enunciado_general" => $problema->enunciado_general,
    		"preguntas" => $problema->preguntas
    	);
		// Pasar los datos al template pregunta-sola y guardar el contenido.
		$problemaTex = $template_problema->render($problemaNormal);
	}
	return $problemaTex;
}

?>
