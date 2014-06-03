<?php
require_once("./mappers/problema_mapper.php");

	$method = $_SERVER['REQUEST_METHOD'];
	if ($method!="DELETE") {
		header('HTTP/1.1 405 Method Not Allowed');
		echo("Only DELETE method allowed");
		return;
	}
	$id = $_GET["id_problema"];

	//Eliminar problema de base de datos.
	$ProblemaMapper = new ProblemaMapper;
	$ProblemaMapper->DeleteProblem($id);

	echo ("OK. Borrado el problema $id");
?>
