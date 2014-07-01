<?php
try 
{
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $name = $_GET["name"];
        if (!$name) {  // si no viene el nombre de fichero como parámetro
            header("HTTP/1.1 400 Bad request");
            exit(0);
        }
        if (substr($name,-4)!== ".zip") { // Si el nombre no acaba en .zip
            header("HTTP/1.1 403 Forbidden");
            exit(0);
        }
        $name = urldecode($name);
        if (!file_exists($name)) {  // Si no se encuentra
            header("HTTP/1.1 404 Not found");
            echo "El fichero " . $name . " no se encuentra. Pruebe a generarlo de nuevo.";
            exit(0);
        }
        // Si pasa los test anteriores
        // Provocar la descarga en el navegador.
        sendFileToBrowser($name);
        // Borrar fichero
        unlink($name);
	}
}
catch(PDOException $e)
{
    echo $e->getMessage();
}


// Función para enviar el fichero al navegador para que se inicie la descarga del zip.
function sendFileToBrowser($filepath) {
	header('Content-Type: application/zip');
	header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
	header('Content-Length: ' . filesize($filepath));
	ob_flush();
	ob_clean(); 
	readfile($filepath);
}
?>
