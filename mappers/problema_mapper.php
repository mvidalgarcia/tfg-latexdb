<?php

require_once("./model/problema.php");
require_once("./model/pregunta.php");
require_once("./model/tag.php");
require_once("./singleton_db.php");

class ProblemaMapper
{
    protected static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }

/* TODO: ESTA PARTE SE ELIMINARÁ, SE USA COMO GUÍA DE ALGUNAS FUNCIONES.	

	
	public function Insert($Problema)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO problema (enunciado_general, resumen) value (:enunciado_general, :resumen)"); 
        $STH->bindParam(':enunciado_general', $Problema->enunciado_general);
        $STH->bindParam(':resumen', $Problema->resumen);
        $STH->execute(); 
        $Problema->id_problema = self::$dbh->lastInsertId();
    }

	public function Update($Problema)
    {
        $STH = self::$dbh->prepare(
         "UPDATE problema SET enunciado_general = :enunciado_general, resumen = :resumen WHERE id_problema = :id_problema"); 
        $STH->bindParam(':enunciado_general', $Problema->enunciado_general);
        $STH->bindParam(':resumen', $Problema->resumen);
		$STH->execute(); 
    } 

	public function Save($Problema)
    {
        if ( ($Problema->id_problema === NULL) or (empty($Problema->id_problema)) )
            self::Insert($Problema);
        else
            self::Update($Problema);
	}
	
	public function Delete($Problema)
    {
        $STH = self::$dbh->prepare('DELETE FROM problema WHERE id_problema = :id_problema');
        $STH->bindParam(':id_problema', $Problema->id_problema);
        $STH->execute(); 
    }

	public function FindById($id)
    {
        $STH = self::$dbh->prepare('SELECT * FROM problema WHERE id_problema = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');  
        $STH->execute(); 
        return $STH->fetch();
    }

    public function FindAll()
    {
        $STH = self::$dbh->prepare('SELECT * FROM problema');
        //$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');  
        $STH->execute();
        return $STH->fetchAll();
    } 
*/


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
		$STH = self::$dbh->prepare('SELECT preg.id_pregunta, preg.enunciado, preg.solucion, 
											preg.explicacion, preg.puntuacion, preg.posicion 
											FROM pregunta as preg WHERE id_problema = :id
											ORDER BY preg.posicion');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Pregunta');  
        $STH->execute(); 
        $preguntas = $STH->fetchAll();

		//Obtener nombres de tags
		$STH = self::$dbh->prepare('SELECT tag.nombre 
									FROM problema_tag as ptag 
										JOIN tag ON ptag.id_tag = tag.id_tag 
									WHERE id_problema = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');  
        $STH->execute(); 
        $tags = $STH->fetchAll();

		//TODO: Seguramente haya que obtener las imágenes.
	
		$problema->preguntas = $preguntas;
		$problema->tags = $tags;

		return $problema;
    }

	
	/* Función que obtiene toda la información nececesaria para listar 
	 * todos los problemas. Incluye resúmenes de problemas y tags asociados */
	public function FindProblemList()
    {
		// Obtener todos los ids de problemas y resúmenes
        $STH = self::$dbh->prepare('SELECT id_problema, resumen FROM problema');
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
		
		// TODO: Cuando se implemente la parte de doc_final-problema hay que tener en cuenta
		// cuando se borren problemas que pertenezcan a un doc_final
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


}


?>
