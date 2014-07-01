<?php

require_once("./model/problema.php");
require_once("./model/pregunta.php");
require_once("./model/tag.php");
require_once("./singleton_db.php");

class ProblemaMapper
{
    public static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }


	/* Función que obtiene toda la información nececesaria de un problema 
	 * incluyendo sus preguntas y sus tags asociados */
	public function FindProblemById($id)
    {
        // Obtener datos problema
		$STH = self::$dbh->prepare('SELECT * FROM problema WHERE id_problema = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');  
        $STH->execute(); 
        $problema = $STH->fetch();
		
		// Obtener datos preguntas
		$problema->preguntas = $this->FindQuestionsByIdProblem($id);
		
		//Obtener nombres de tags
		$problema->tags = $this->FindTagsByIdProblem($id);

		//TODO: Seguramente haya que obtener las imágenes.
	
		// Obtener los ids de documentos a los que pertence el problema.
		$problema->id_docs_cerrados_publicados = $this->GetIdDocs($problema, "published");
		$problema->id_docs_abiertos = $this->GetIdDocs($problema, "open");

		return $problema;
    }

	
	/* Función que obtiene toda la información nececesaria para listar 
	 * todos los problemas. Incluye resúmenes de problemas, tags asociados,
	 * número de preguntas e ids de documentos abiertos o cerrados/publicados
	 * asociados a los mismos. */
	public function FindProblemList()
    {
		// Obtener todos los ids de problemas y resúmenes
        $STH = self::$dbh->prepare('SELECT id_problema, resumen, id_padre FROM problema');
		$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');       
		$STH->execute();
        $problemas = $STH->fetchAll();
		
		// Iterar sobre los ids de problema y almacenar los tags asociados a cada uno y el número de preguntas.
		foreach($problemas as $problema){
			// Obtener los tags.
			$problema->tags = $this->FindTagsByIdProblem($problema->id_problema);

			// Obtener el número de preguntas.
			$problema->num_preguntas = $this->GetNumberOfQuestions($problema->id_problema);

			// Obtener la puntuación total preguntas.
			$problema->puntos = $this->GetScore($problema->id_problema);


			// Obtener los ids de documentos 'cerrados/publicados' en los que está presente el problema.
			$problema->id_docs_cerrados_publicados = $this->GetIdDocs($problema, "published");
			// Obtener los ids de documentos 'abiertos' en los que está presente el problema.
			$problema->id_docs_abiertos = $this->GetIdDocs($problema, "open");
		}

		return $problemas; 
    }


	/* Función que guarda un problema nuevo con sus
	 * preguntas, tags e imágenes asociados en base de datos */
 	public function InsertProblem($Problema)
    {
        // Guardar problema.
		$STH = self::$dbh->prepare(
         "INSERT INTO problema (enunciado_general, resumen) values (:enunciado_general, :resumen)"); 
        $STH->bindParam(':enunciado_general', $Problema->enunciado_general);
        $STH->bindParam(':resumen', $Problema->resumen);
        $STH->execute(); 
        $Problema->id_problema = self::$dbh->lastInsertId();

		// Guardar preguntas.
		foreach ($Problema->preguntas as $pregunta) {
			$this->InsertPregunta($pregunta, $Problema->id_problema);	
		}
		// Guardar tags.
		$this->SaveTags($Problema);

		// TODO: Guardar imágenes. Afecta a tablas problema_imagen e imagen.
    }


	/* Función que elimina un problema. Gracias a "on DELETE cascade"
	 * se eliminan todas las relaciones asociadas con ejercicios, tags e imágenes. */
	public function DeleteProblem($IdProblema)
    {
        $STH = self::$dbh->prepare('DELETE FROM problema WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $IdProblema);
        $STH->execute(); 
		
    }


	/* Función que actualiza un problema y sus preguntas,
	 * tags e imágenes asociados en base de datos */
 	public function UpdateProblem($Problema)
    {
		// Actualizar los datos comunes del problema.
		$STH = self::$dbh->prepare(
         "UPDATE problema SET enunciado_general = :enunciado_general, resumen = :resumen WHERE id_problema = :id_problema"); 
        $STH->bindParam(':enunciado_general', $Problema->enunciado_general);
        $STH->bindParam(':resumen', $Problema->resumen);
        $STH->bindParam(':id_problema', $Problema->id_problema);
		$STH->execute();

		// Actualizar las preguntas del problema. Primero eliminar todas las relaciones y después insertar las preguntas.
		$STH = self::$dbh->prepare('DELETE FROM pregunta WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $Problema->id_problema);
        $STH->execute();

		foreach ($Problema->preguntas as $pregunta) {
			$this->InsertPregunta($pregunta, $Problema->id_problema);	
		}

		// Actualizar los tags del problema.
		$this->UpdateTags($Problema);

		// TODO: Actualizar imágenes. Afecta a tablas problema_imagen e imagen.

	}



	/******** Funciones auxiliares ********/

	// Función que obtiene todos los datos de las preguntas asociadas
	// a un problema.
	public function FindQuestionsByIdProblem($IdProblema)
	{
		// Obtener datos preguntas
		$STH = static::$dbh->prepare('SELECT preg.id_pregunta, preg.enunciado, preg.solucion, 
											preg.explicacion, preg.puntuacion, preg.posicion 
											FROM pregunta as preg WHERE id_problema = :id
											ORDER BY preg.posicion');
        $STH->bindParam(':id', $IdProblema);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Pregunta');  
        $STH->execute(); 
        $preguntas = $STH->fetchAll();
		return $preguntas;
	}


	// Función que guarda una pregunta en base de datos con su id de problema asociado.
	private function InsertPregunta($Pregunta, $IdProblema)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO pregunta (enunciado, solucion, explicacion, puntuacion, posicion, id_problema) 
						 value (:enunciado, :solucion, :explicacion, :puntuacion, :posicion, :id_problema)"); 
        $STH->bindParam(':enunciado', $Pregunta->enunciado);
        $STH->bindParam(':solucion', $Pregunta->solucion);
        $STH->bindParam(':explicacion', $Pregunta->explicacion);
        $STH->bindParam(':puntuacion', $Pregunta->puntuacion);
        $STH->bindParam(':posicion', $Pregunta->posicion);
        $STH->bindParam(':id_problema', $IdProblema);
        $STH->execute(); 
        $Pregunta->id_pregunta = self::$dbh->lastInsertId();
    }

	
	// Función que busca un tag por su nombre (es posible ya que los nombres
	// de los tags son únicos) y si existe devuelve su id.
	private function FindTagByName($nombre)
    {
        $STH = self::$dbh->prepare('SELECT id_tag FROM tag WHERE nombre = :nombre');
        $STH->bindParam(':nombre', $nombre);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');  
        $STH->execute();
		$tag = $STH->fetch();
        if (empty($tag->id_tag))
			return;
		return $tag->id_tag;
    }

	// Función que busca todos los tags de un problema por el id problema.
	public function FindTagsByIdProblem($IdProblema)
    {
		$STH = static::$dbh->prepare('SELECT tag.nombre 
									FROM problema_tag as ptag 
										JOIN tag ON ptag.id_tag = tag.id_tag 
									WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $IdProblema);
		$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');       
		$STH->execute();
        $tags = $STH->fetchAll();
		return $tags;
	}

	// Función que introduce un tag nuevo y lo relaciona con un problema.
	private function InsertNewTag($Tag, $IdProblema)
    {
        // Guardar tag nuevo.
		$STH = self::$dbh->prepare(
         "INSERT INTO tag (nombre) value (:nombre)"); 
        $STH->bindParam(':nombre', $Tag);
        $STH->execute(); 
        $IdTag = self::$dbh->lastInsertId();

		// Guardar relación problema <-> tag.
		$STH = self::$dbh->prepare(
         "INSERT INTO problema_tag (id_problema, id_tag) values (:id_problema, :id_tag)"); 
        $STH->bindParam(':id_problema', $IdProblema);
        $STH->bindParam(':id_tag', $IdTag);
        $STH->execute(); 

    }

	
	// Función que relaciona un problema con un tag existente.
	private function InsertExistingTag($IdTag, $IdProblema)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO problema_tag (id_problema, id_tag) values (:id_problema, :id_tag)"); 
        $STH->bindParam(':id_problema', $IdProblema);
        $STH->bindParam(':id_tag', $IdTag);
        $STH->execute(); 
    }

	// Función que guarda los tags de un problema nuevo (gestionar los que ya estén en BD).
	// Afecta a tablas problema_tag y tag.
	private function SaveTags($Problema) {
		foreach ($Problema->tags as $tag) {
			$idtag = $this->FindTagByName($tag);
			// Si está vacío, tag nuevo.
			if (empty($idtag) or !(isset($idtag))) {
				$this->InsertNewTag($tag, $Problema->id_problema);	
			}
			// Sino, tag existente.
			else {
				$this->InsertExistingTag($idtag, $Problema->id_problema);
			}
		}
	}	


	// Función que actualiza los tags de un problema cuando se da a la opción 'Editar'. 
	// Elimina todas las relaciones y las vuelve a insertar. Afecta a tablas problema_tag y tag.
	private function UpdateTags($Problema)
	{
		// Eliminar todas las relaciones de tags del problema.
	    $STH = self::$dbh->prepare('DELETE FROM problema_tag WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $Problema->id_problema);
        $STH->execute(); 
		// Guardar los tags gestionando los que ya están en BD.
		$this->SaveTags($Problema);
	}


	// Función que obtiene el número de preguntas de un problema.
	public function GetNumberOfQuestions($IdProblema)
	{
		$STH = static::$dbh->prepare('SELECT count(*) as npreg FROM pregunta WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $IdProblema);
		$STH->execute();
        $info = $STH->fetch();
		return $info['npreg'];
	}

	// Función que obtiene la puntuación total de un problema
	public function GetScore($IdProblema)
	{
		$STH = static::$dbh->prepare('SELECT sum(puntuacion) as score FROM pregunta WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $IdProblema);
		$STH->execute();
        $info = $STH->fetch();
		return $info['score'];
	}


	private function GetIdDocs($Problema, $tipo)
	{
		if ($tipo == "published") {
			$STH = self::$dbh->prepare("SELECT pdf.id_doc AS id_doc
										FROM problema_doc_final AS pdf 
										JOIN doc_final AS df ON pdf.id_doc = df.id_doc 
										WHERE pdf.id_problema = :id_problema AND
											(df.estado = 'cerrado' OR df.estado = 'publicado')");
		}
		
		elseif ($tipo == "open") {
			$STH = self::$dbh->prepare("SELECT pdf.id_doc AS id_doc
										FROM problema_doc_final AS pdf 
										JOIN doc_final AS df ON pdf.id_doc = df.id_doc 
										WHERE pdf.id_problema = :id_problema AND df.estado = 'abierto'");
		}

        $STH->bindParam(':id_problema', $Problema->id_problema);
		$STH->setFetchMode(PDO::FETCH_ASSOC);       
		$STH->execute();
        $resultados = $STH->fetchAll();
		return $resultados;
	}

}
?>
