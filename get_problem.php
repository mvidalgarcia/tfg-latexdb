<?php
require_once("./mappers/problema_mapper.php");
require_once("./mappers/problema_tag_mapper.php");

try 
{
	if (isset($_GET["id_problema"])) 
    {
        $id = $_GET["id_problema"];
    	
		// Guardar problema buscadolo por su ID
        $MapperProblema = new ProblemaMapper;
        $Problema = $MapperProblema->FindById($id);
		
		// Guardar todos los tags asociados al id del problema
		$MapperProblemaTag = new Problema_TagMapper;
		$Tags = $MapperProblemaTag->FindNameTagsByIdProblema($id);

    }
	
	//RETURN
    echo "<pre>";
        var_dump(json_encode(utf8ize($Problema))); 
    echo "</pre>";
	
	echo "<pre>";
        var_dump(json_encode(utf8ize($Tags))); 
    echo "</pre>";
	
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

// Función para pasar a UTF-8 todos los caracteres de un array.
function utf8ize($d) {
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } else {
        return utf8_encode($d);
    }
    return $d;
}
?>
