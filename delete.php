<?php

require("amigo_mapper.php");

try 
{
    if ( !isset($_REQUEST["id"]) ) 
    {
        echo "Error: no hay id";
        exit();
    }

    $id = $_REQUEST["id"];
    
    $Mapper = new AmigoMapper;
    $NuevoAmigo = $Mapper->FindById($id);
    $Mapper->Delete($NuevoAmigo);

    header('Location: index.php');
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
