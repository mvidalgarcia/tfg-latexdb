<?php

require_once("./Twig/lib/Twig/Autoloader.php");
require_once("./mappers/problema_mapper.php");
require_once("./mappers/pregunta_mapper.php");

// Load the view
Twig_Autoloader::register();
$loader = new Twig_Loader_Filesystem('./');
$twig = new Twig_Environment($loader);
$template = $twig->loadTemplate('index.phtml');
    
try 
{
    // Find all the 'problems'
    $Mapper = new ProblemaMapper;
    $res_problema = $Mapper->FindAll();

	// Find all the 'questions'
    $Mapper = new PreguntaMapper;
    $res_pregunta = $Mapper->FindAll();

    // Show the view with the problems
    $template->display(array("problema" => $res_problema, "pregunta" => $res_pregunta));
}
catch(PDOException $e)
{
    echo $e->getMessage();
}

?>
