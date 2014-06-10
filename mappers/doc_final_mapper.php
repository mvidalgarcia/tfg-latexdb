<?php

require_once("./model/problema.php");
require_once("./model/pregunta.php");
require_once("./singleton_db.php");

class DocFinalMapper
{
    protected static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }

	/* Función que obtiene toda la información nececesaria para listar 
	 * todos los documentos. Incluye titulación, asignatura, convocatoria,
	 * fecha y estado de los documentos */
	public function FindDocList()
    {
        $STH = self::$dbh->prepare('SELECT id_doc, titulacion, asignatura, convocatoria,
									fecha, estado FROM doc_final');
		$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'DocFinal');       
		$STH->execute();
        $docs = $STH->fetchAll();
		return $docs; 
    }
}
?>
