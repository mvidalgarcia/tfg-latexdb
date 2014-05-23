<?php

require_once("./model/problema_tag.php");
require_once("./singleton_db.php");

class Problema_TagMapper
{
    protected static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }

	
	public function Insert($Problema_Tag)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO problema_tag (id_problema, id_tag) value (:id_problema, :id_tag)"); 
        $STH->bindParam(':id_problema', $Problema_Tag->id_problema);
        $STH->bindParam(':id_tag', $Problema_Tag->id_tag);
        $STH->execute(); 
    }

	public function Delete($Problema_Tag)
    {
        $STH = self::$dbh->prepare('DELETE FROM problema_tag WHERE id_problema = :id_problema AND id_tag = :id_tag');
        $STH->bindParam(':id_problema', $Problema_Tag->id_problema);
        $STH->bindParam(':id_tag', $Problema_Tag->id_tag);
        $STH->execute(); 
	}

 	public function FindNameTagsByIdProblema($id)
    {
        $STH = self::$dbh->prepare('select problema_tag.id_problema, tag.nombre 
									from problema_tag inner join tag on problema_tag.id_tag = tag.id_tag 
									where problema_tag.id_problema = :id_problema');
        $STH->bindParam(':id_problema', $id);
		//$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema_Tag');  
        $STH->execute(); 
        return $STH->fetchAll();
    }

    public function FindAll()
    {
        $STH = self::$dbh->prepare('SELECT * FROM problema_tag');
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Problema_Tag');  
        $STH->execute();
        return $STH->fetchAll();
    } 

}

?>
