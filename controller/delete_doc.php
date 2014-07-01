<?php
require_once("../mappers/doc_final_mapper.php");

	$method = $_SERVER['REQUEST_METHOD'];
	if ($method!="DELETE") {
		header('HTTP/1.1 405 Method Not Allowed');
		echo("Only DELETE method allowed");
		return;
	}
	$id = $_GET["id_doc"];

	//Eliminar documento de base de datos.
	$DocFinalMapper = new DocFinalMapper;
	$DocFinalMapper->DeleteDoc($id);

	echo ("OK. Borrado el documento $id.");
?>
