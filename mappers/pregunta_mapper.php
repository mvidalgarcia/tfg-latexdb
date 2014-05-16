<?php

require("./model/pregunta.php");
require("./app.php");

class PreguntaMapper
{
    protected static $dbh;
        
    function __construct() 
    {  
        if ( !isset(self::$dbh) ) 
            self::$dbh = App::ConnectDB();
    }

	public function Insert($Pregunta)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO pregunta (enunciado, solucion, explicacion, puntuacion, posicion, id_problema) 
						 value (:enunciado, :solucion, :explicacion, :puntuacion, :posicion, :id_problema)"); 
        $STH->bindParam(':enunciado', $Pregunta->enunciado);
        $STH->bindParam(':solucion', $Pregunta->solucion);
        $STH->bindParam(':explicacion', $Pregunta->explicacion);
        $STH->bindParam(':puntuacion', $Pregunta->puntuacion);
        $STH->bindParam(':posicion', $Pregunta->posicion);
        $STH->bindParam(':id_problema', $Pregunta->id_problema);
        $STH->execute(); 
        $Pregunta->id_pregunta = self::$dbh->lastInsertId();
    }

	public function Update($Pregunta)
    {
        $STH = self::$dbh->prepare(
         "UPDATE pregunta SET enunciado = :enunciado, solucion = :solucion, explicacion = :explicacion
							  puntuacion = :puntuacion, posicion = :posicion, id_problema = :id_problema 
							  WHERE id_pregunta = :id_pregunta"); 
        $STH->bindParam(':enunciado', $Pregunta->enunciado);
        $STH->bindParam(':solucion', $Pregunta->solucion);
        $STH->bindParam(':explicacion', $Pregunta->explicacion);
        $STH->bindParam(':puntuacion', $Pregunta->puntuacion);
        $STH->bindParam(':posicion', $Pregunta->posicion);
        $STH->bindParam(':id_problema', $Pregunta->id_problema);
        $STH->execute(); 
    } 

	public function Save($Pregunta)
    {
        if ( ($Pregunta->id_pregunta === NULL) or (empty($Pregunta->id_pregunta)) )
            self::Insert($Pregunta);
        else
            self::Update($Pregunta);
	}
	
	public function Delete($Pregunta)
    {
        $STH = self::$dbh->prepare('DELETE FROM pregunta WHERE id_pregunta = :id_pregunta');
        $STH->bindParam(':id_pregunta', $Pregunta->id_pregunta);
        $STH->execute(); 
    }

	public function FindById($id)
    {
        $STH = self::$dbh->prepare('SELECT * FROM pregunta WHERE id_pregunta = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Pregunta');  
        $STH->execute(); 
        return $STH->fetch();
    }

}

?>
