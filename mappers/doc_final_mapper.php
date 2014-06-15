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
         "INSERT INTO doc_final (titulacion, asignatura, convocatoria, fecha, estado, instrucciones) 
								 values (:titulacion, :asignatura, :convocatoria, :fecha, :estado, :instrucciones)"); 
        $STH->bindParam(':titulacion', $Doc->titulacion);
        $STH->bindParam(':asignatura', $Doc->asignatura);
        $STH->bindParam(':convocatoria', $Doc->convocatoria);
        $STH->bindParam(':fecha', $Doc->fecha);
        $STH->bindParam(':estado', $Doc->estado);
        $STH->bindParam(':instrucciones', $Doc->instrucciones);
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


	/* Función que actualiza un documento y sus problemas
	 * asociados en base de datos */
 	public function UpdateDoc($Doc)
    {
		// Actualizar los datos comunes del documento.
		$STH = self::$dbh->prepare(
         "UPDATE doc_final SET titulacion = :titulacion, asignatura = :asignatura, convocatoria = :convocatoria,
							   fecha = :fecha, estado = :estado, instrucciones = :instrucciones WHERE id_doc = :id_doc"); 
        $STH->bindParam(':titulacion', $Doc->titulacion);
        $STH->bindParam(':asignatura', $Doc->asignatura);
        $STH->bindParam(':convocatoria', $Doc->convocatoria);
        $STH->bindParam(':fecha', $Doc->fecha);
        $STH->bindParam(':estado', $Doc->estado);
        $STH->bindParam(':instrucciones', $Doc->instrucciones);
        $STH->bindParam(':id_doc', $Doc->id_doc);
		$STH->execute();

		// Actualizar los problemas del documento. Primero eliminar todas las relaciones y después insertar los problemas.
		$STH = self::$dbh->prepare('DELETE FROM problema_doc_final WHERE id_doc = :id_doc');
        $STH->bindParam(':id_doc', $Doc->id_doc); 
        $STH->execute();

		// Guardar asociación con problemas.
		foreach ($Doc->problemas as $problema) {
			$this->InsertDocProb($problema, $Doc->id_doc);	
		}

	}

	/******** Funciones auxiliares ********/
	
	/* Función que devuelve los ids, posiciones, resúmenes y tags de problemas asociados
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

		// Iterar sobre los ids de problema y almacenar los tags asociados a cada uno.
		foreach($problemas as $problema){
			$STH = self::$dbh->prepare('SELECT t.nombre FROM problema as prob 
										JOIN problema_tag as pt ON prob.id_problema=pt.id_problema 
										JOIN tag as t ON pt.id_tag=t.id_tag
										WHERE pt.id_problema=:id_problema');
        	$STH->bindParam(':id_problema', $problema->id_problema);
			$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');       
			$STH->execute();
        	$tags = $STH->fetchAll();
			$problema->tags = $tags;
		}

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
