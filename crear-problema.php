<!-- 
******************************************************************
Fichero:    crear-problema.php
Asignatura: Trabajo Fin de Grado
Titulación: GIITI
Autor     : Marco Vidal García
Universidad de Oviedo
******************************************************************
-->

<?php
require_once("./Twig/lib/Twig/Autoloader.php");
require_once("./mappers/problema_mapper.php");
require_once("./mappers/pregunta_mapper.php");
require_once("./mappers/tag_mapper.php");
require_once("./mappers/problema_tag_mapper.php");

// Declaración de variables.
$resumenErr = $tagsErr = "";
$enunciadogeneral = $resumen = $tagsArray = "";
$enunciadoErr = $puntuacionErr = "";
$enunciado = "";
$tagsArray = array();

// Validación de datos introducidos en el formulario.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$enunciadogeneral = test_input($_POST['enunciadogeneral']);

	// Campos del problema.
	if (empty($_POST['resumen']))
		$resumenErr = "Es obligatorio introducir un resumen del ejercicio.";
	else
		$resumen = test_input($_POST['resumen']);

	if (empty($_POST['tags']))
		$tagsErr = "Es obligatorio introducir al menos un tag.";
	else
	{
		$tagsArray = tags_treatment($_POST['tags']);
	}

	if (!empty($_POST['npreg']))
		$npreg = $_POST['npreg'];

	
	// Campos de las preguntas del problema.
	for ($i=1; $i <= $npreg; $i++)
	{
		if (empty($_POST["enunciado$i"]))
			$enunciadoErr = "Es obligatorio introducir el enunciado de la pregunta.";
		else
			$enunciado[$i] = test_input($_POST["enunciado$i"]);

		$solucion[$i] = test_input($_POST["solucion$i"]);
		$explicacion[$i] = test_input($_POST["explicacion$i"]);
		
		if (empty($_POST["puntuacion$i"]))
			$puntuacionErr = "Es obligatorio introducir la puntuación de la pregunta.";
		elseif ( is_int($_POST["puntuacion$i"]) or $_POST["puntuacion$i"] <= 0 )
			$puntuacionErr = "La puntuación introducida debe ser un entero positivo.";
		else
			$puntacion[$i] = test_input($_POST["puntuacion$i"]);
	
		// En el caso de que no se produzca ningun Err, meter datos en BD. Cargar mappers, comprobar qeu se le da el boton de meter en BD, etc.
		// Introducir en BD el problema.
		// Checkear primero si existen los tags en BD. En caso afirmativo obtener su id.
		// Faltaría contemplar el tema de las imágenes.
	
		// Introducir en la BD el ejercicio

		
	}
	

}

// Función para eliminar caracteres HTML, backslashes y espacios extras.
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}

// Función para el tratamiento de los tags.
function tags_treatment($data) {
	$data = test_input($data);
	$array_tags = preg_split(',', $data , NULL, PREG_SPLIT_NO_EMPTY);
	return $array_tags;
}

?>

<!---------------------------------------------------------------->

<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <title>Crear ejercicio</title>
        <link rel="stylesheet" href="css/error.css">
		<!--  Enlazado de la biblioteca JQuery -->
    	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
		<!-- Enlazado a mi fichero Javascript/JQuery -->
		<script type="text/javascript" src="javascript/functions_problemas.js" charset="utf-8"></script>
    </head>
    <body>
		<h4>Datos comunes del ejercicio</h4></br>
		<form method="post" id="formProblemas" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>">
			<label for="enunciadogeneral">Enunciado general: </label><br/>
			<textarea id="enunciadogeneral" name="enunciadogeneral" rows="3" cols="80">Introduce aquí el enunciado general del ejercicio...</textarea><br/>
			<label for="resumen">Resumen del ejercicio: </label><span class="error">* <?php echo $resumenErr;?></span><br/>
			<textarea id="resumen" name="resumen" rows="3" cols="80">Introduce aquí el resumen del ejercicio...</textarea><br/>
			<label for="tags">Tags: </label><span class="error">* <?php echo $tagsErr;?></span><br/>
			<input type="text" id="tags" name="tags"/><br/>
			</br>
		</form>
<br/><br/>
<button id='cancelar'>Cancelar</button><button id='guardar'>Guardar ejercicio</button>
    </body>
</html>
