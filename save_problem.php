<?php
require_once("./mappers/problema_mapper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents("php://input");
	$problema = json_decode($postdata);
	
	// Caso de problema nuevo
	if ($problema->id_problema == "Nuevo") {

		// Eliminar comas y espacios en blanco
		$problema->tags = tagsTreatment($problema->tags);
		
		$ProblemaMapper = new ProblemaMapper;
		$ProblemaMapper->InsertProblema($problema);
		
		echo "Problema nuevo guardado.";
	}
	// Caso de problema editado
	else {
		// TODO: Averiguar si hay alguna forma de saber cuales son los campos que se editaron.
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
