<?php

require_once("../model/problema.php");
require_once("../model/doc_final.php");
require_once("../mappers/problema_mapper.php");
require_once("../singleton_db.php");

class DocFinalMapper
{
    public static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }
	

	/* Función que obtiene toda la información nececesaria de un documento 
	 * final, incluyendo sus problemas asociados */
	public function FindDocById($id)
    {
        // Obtener datos comunes del documento.
		$STH = self::$dbh->prepare('SELECT * FROM doc_final WHERE id_doc = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'DocFinal');  
        $STH->execute(); 
        $doc = $STH->fetch();
		
		// Obtener problemas asociados
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

	/* Función que actualiza el estado de un documento */
 	public function ChangeDocStatus($IdDoc, $Estado)
    {
		$STH = self::$dbh->prepare(
         "UPDATE doc_final SET estado = :estado WHERE id_doc = :id_doc"); 
        $STH->bindParam(':estado', $Estado);
        $STH->bindParam(':id_doc', $IdDoc);
		$STH->execute();
	}
	

	/* Función que obtiene toda la información necesaria para
	 * generar un documento */
	public function GetTeXInfoDoc($IdDoc)
	{
		// Obtener los datos comunes del documento necesarios para la generacion del TeX.
		$STH = self::$dbh->prepare('SELECT asignatura, convocatoria, instrucciones, fecha 
									FROM doc_final 
									WHERE id_doc = :id');
        $STH->bindParam(':id', $IdDoc);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'DocFinal');  
        $STH->execute(); 
        $doc = $STH->fetch();
		
		// Obtener datos comunes de los problemas asociados.
		$doc->problemas = $this->FindProblemsByIdDoc($IdDoc);

		// Para cada uno de los problemas, obtener los campos de sus preguntas.
		foreach ($doc->problemas as $problema)
			$problema->preguntas = ProblemaMapper::FindQuestionsByIdProblem($problema->id_problema);

		return $doc;
	}


	/******** Funciones auxiliares ********/
	
	/* Función que devuelve los ids, posiciones, resúmenes y tags de problemas asociados
	 * a un documento, asi como su puntuación y número de preguntas. */
	private function FindProblemsByIdDoc($id)
	{
		// Obtener datos de problemas asociados al documento.
		$STH = self::$dbh->prepare('SELECT pdoc.id_problema, pdoc.posicion, prob.resumen, prob.enunciado_general 
									FROM problema_doc_final AS pdoc 
									JOIN problema AS prob ON pdoc.id_problema=prob.id_problema 
									WHERE id_doc=:id 
									ORDER BY pdoc.posicion');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');  
        $STH->execute(); 
        $problemas = $STH->fetchAll();

        // Iterar sobre los ids de problema y almacenar los tags asociados a cada uno.
		foreach($problemas as $problema){
			$problema->tags = ProblemaMapper::FindTagsByIdProblem($problema->id_problema); 
			$problema->num_preguntas = ProblemaMapper::GetNumberOfQuestions($problema->id_problema);
            $problema->puntos = ProblemaMapper::GetScore($problema->id_problema);
			//TODO: Habría que recoger las imágenes también.
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
