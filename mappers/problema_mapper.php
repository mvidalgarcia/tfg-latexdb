<?php

require("./model/problema.php");
require("./app.php");

class ProblemaMapper
{
    protected static $dbh;
        
    function __construct() 
    {  
        if ( !isset(self::$dbh) ) 
            self::$dbh = App::ConnectDB();
		
    }

	public function Insert($Problema)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO problema (enunciado_general, resumen) value (:enunciado_general, :resumen)"); 
        $STH->bindParam(':enunciado_general', $Problema->enunciado_general);
        $STH->bindParam(':resumen', $Problema->resumen);
        $STH->execute(); 
        $Pregunta->id_problema = self::$dbh->lastInsertId();
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
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema');  
        $STH->execute();
        return $STH->fetchAll();
    } 

}

?>
