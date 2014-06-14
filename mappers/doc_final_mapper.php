<?php

require_once("./model/problema.php");
require_once("./model/doc_final.php");
require_once("./singleton_db.php");

class DocFinalMapper
{
    protected static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }
	

	/* Función que obtiene toda la información nececesaria de un problema 
	 * incluyendo sus preguntas y sus tags asociados */
	public function FindDocById($id)
    {
        // Obtener datos comunes del documento.
		$STH = self::$dbh->prepare('SELECT * FROM doc_final WHERE id_doc = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'DocFinal');  
        $STH->execute(); 
        $doc = $STH->fetch();
		
		$problemas = $this->FindProblemsByIdDoc($id);

		$doc->problemas = $problemas;
		return $doc;
    }


	/* Función que obtiene toda la información necesaria para listar 
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


	/* Función que guarda un documento nuevo con sus
	 * ids de problemas asociados en base de datos */
 	public function InsertDoc($Doc)
    {
        // Guardar documento.
		$STH = self::$dbh->prepare(
         "INSERT INTO doc_final (titulacion, asignatura, convocatoria, fecha, estado) 
								 values (:titulacion, :asignatura, :convocatoria, :fecha, :estado)"); 
        $STH->bindParam(':titulacion', $Doc->titulacion);
        $STH->bindParam(':asignatura', $Doc->asignatura);
        $STH->bindParam(':convocatoria', $Doc->convocatoria);
        $STH->bindParam(':fecha', $Doc->fecha);
        $STH->bindParam(':estado', $Doc->estado);
        $STH->execute(); 
        $Doc->id_doc = self::$dbh->lastInsertId();

		// Guardar asociación con problemas.
		foreach ($Doc->problemas as $problema) {
			$this->InsertDocProb($problema, $Doc->id_doc);	
		}
    }

	
	/* Función que elimina un documento. Gracias a "on DELETE cascade"
	 * se eliminan todas las relaciones asociadas con problemas. */
	public function DeleteDoc($IdDoc)
    {
        $STH = self::$dbh->prepare('DELETE FROM doc_final WHERE id_doc = :id_doc');
        $STH->bindParam(':id_doc', $IdDoc);
        $STH->execute(); 
    }

	/******** Funciones auxiliares ********/
	
	/* Función que devuelve los ids, posiciones y resúmenes de problemas asociados
	 * a un documento. */
	private function FindProblemsByIdDoc($id)
	{
		// Obtener datos de problemas asociados al documento.
		$STH = self::$dbh->prepare('SELECT pdoc.id_problema, pdoc.posicion, prob.resumen 
									FROM problema_doc_final AS pdoc 
									JOIN problema AS prob ON pdoc.id_problema=prob.id_problema 
									WHERE id_doc=:id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');  
        $STH->execute(); 
        $problemas = $STH->fetchAll();

		return $problemas;
	}

	
	/* Función que inserta en la base de datos la relación entre
	 * documentos y problemas, incluída su posición. */
	private function InsertDocProb($problema, $id_doc)
	{
		$STH = self::$dbh->prepare(
         "INSERT INTO problema_doc_final (id_doc, id_problema, posicion) 
								 values (:id_doc, :id_problema, :posicion)"); 
        $STH->bindParam(':id_doc', $id_doc);
        $STH->bindParam(':id_problema', $problema->id_problema);
        $STH->bindParam(':posicion', $problema->posicion);
		$STH->execute();
	}

}
?>
