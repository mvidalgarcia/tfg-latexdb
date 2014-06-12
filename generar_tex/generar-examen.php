<?php
// Es un ejemplo con datos "fijos" en lugar
// de sacarlos del modelo, vía algún mapper
require_once("../Twig/lib/Twig/Autoloader.php");

// Cargar la vista
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('template-examen.tex');
    
try 
{
    // Relleno los datos de un examen de ejemplo
    // Esto debería salir de la base de datos, dado el id_documento
    // y otros parámetros que puedan recibirse por POST
    $examenEjemplo = array(
        "con_respuestas" => true,
        "con_explicaciones" => false,
        "asignatura" => "Sistemas Distribuidos",
        "convocatoria" => "Extraordinaria de Enero",
        "fecha" => "17/01/2014",
        "instrucciones" => "Todas las preguntas valen igual, bla bla..."
    );
    $examenEjemplo["problemas"] = array();
    $examenEjemplo["problemas"][0] = array("filename" => "foo-bar-4-1237");
    $examenEjemplo["problemas"][1] = array("filename" => "foo-bar-3-6381");
    $examenEjemplo["problemas"][2] = array("filename" => "bar-foo-3-3284");
    $examenEjemplo["problemas"][3] = array("filename" => "fro-bra-4-0128");
    $examenEjemplo["problemas"][4] = array("filename" => "faa-bor-1-3634");

    // Mostrar el examen, en realidad debería escribirse en disco, en 
    // una carpeta temporal (a investigar cómo)
    $template->display($examenEjemplo);
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
