<?php
require_once("./mappers/doc_final_mapper.php");

try 
{
    // Obtener de BD todos los 'documentos finales'
    $DocFinalMapper = new DocFinalMapper;
    $docs = $DocFinalMapper->FindDocList();

	//RETURN
    header('Content-type: application/json');
    echo(json_encode($docs,JSON_NUMERIC_CHECK)); 
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
