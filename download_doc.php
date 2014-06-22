<?php
require_once("./mappers/doc_final_mapper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents("php://input");
	$info = json_decode($postdata);
	$info["con_soluciones"]
	$info["con_explicaciones"]
	
	$info["id_doc"]	
// Crear el mapper para obtener documentos.
//	$DocFinalMapper = new DocFinalMapper;

	// Guardar problema nuevo en base de datos.
//	$DocFinalMapper->InsertDoc($doc);
//	echo "OK. Guardado el documento $doc->id_doc.";
}

?>
