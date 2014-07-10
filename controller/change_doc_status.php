<?php
require_once("../mappers/doc_final_mapper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents("php://input");
	$info = json_decode($postdata);

	// Crear el mapper para cambiar el estado del documento.
	$DocFinalMapper = new DocFinalMapper;

	// Gambiar el estado del documento en base de datos.
	$DocFinalMapper->ChangeDocStatus($info->id_doc, $info->nuevo_estado);
	echo "OK. Cambiado el estado del documento $info->id_doc por $info->nuevo_estado.";
}


?>
