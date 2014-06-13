<?php
require_once("./mappers/doc_mapper.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$postdata = file_get_contents("php://input");
	$doc = json_decode($postdata);
	
	// Crear el mapper para almacenar documentos.
	$DocFinalMapper = new DocFinalMapper;

	// Caso de documento nuevo.
	if ($doc->id_doc == "Nuevo") {

		// Guardar problema nuevo en base de datos.
		$DocFinalMapper->InsertDoc($doc);
		
		echo "Documento nuevo guardado.";
	}
	// Caso de documento editado.
	else {
		// Actualizar documento en base de datos.
		$DocFinalMapper->UpdateDoc($doc);

		echo "Documento editado.";
	}

	echo "OK. Se supone que se ha guardado un documento.";
}

?>
