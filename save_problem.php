<?php
require_once("./mappers/problema_mapper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents("php://input");
	$problema = json_decode($postdata);
	
	// Eliminar comas y espacios en blanco.
	$problema->tags = tagsTreatment($problema->tags);
	// Crear el mapper para almacenar problemas.
	$ProblemaMapper = new ProblemaMapper;

	// Caso de problema nuevo o copia
	if ($problema->id_problema == "Nuevo" or $problema->id_problema == "Copia") {

		// Guardar problema nuevo en base de datos.
		$ProblemaMapper->InsertProblem($problema);
		
		echo "Problema nuevo guardado.";
	}
	// Caso de problema editado
	else {
		// Actualizar problema en base de datos.
		$ProblemaMapper->UpdateProblem($problema);

		echo "Problema editado.";
	}

	echo "OK. Se supone que se ha guardado un problema.";
}

// Función tratamiento de string de tags. Recibe string y devuelve array.
function tagsTreatment($Tags) {
	// Eliminar comas al final o al principio del string, 
	// ya que de ser así se crearían tags vacíos ("").
	$Tags = trim($Tags, ',');
		
	// Convertir el string de tags a array dividiendo por comas.
	$Tags = explode(',', $Tags);
		
	// Eliminar los espacios en blanco al principio o al final de cada tag.
	foreach ($Tags as &$tag) {
		$tag = trim($tag);
	}
	unset($tag); // Es necesario liberar al pasar por referencia.

	return $Tags;
}
?>
