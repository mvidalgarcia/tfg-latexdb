<?php

require_once("./model/tag.php");
require_once("./singleton_db.php");

class TagMapper
{
    protected static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }

	
	public function Insert($Tag)
    {
        $STH = self::$dbh->prepare(
         "INSERT INTO tag (nombre) value (:nombre)"); 
        $STH->bindParam(':nombre', $Tag->nombre);
        $STH->execute(); 
        $Tag->id_tag = self::$dbh->lastInsertId();
    }

	public function Update($Tag)
    {
        $STH = self::$dbh->prepare(
         "UPDATE tag SET nombre = :nombre WHERE id_tag = :id_tag"); 
        $STH->bindParam(':nombre', $Tag->nombre);
		$STH->execute(); 
    } 

	public function Save($Tag)
    {
        if ( ($Tag->id_tag === NULL) or (empty($Tag->id_tag)) )
            self::Insert($Tag);
        else
            self::Update($Tag);
	}
	
	public function Delete($Tag)
    {
        $STH = self::$dbh->prepare('DELETE FROM tag WHERE id_tag = :id_tag');
        $STH->bindParam(':id_tag', $Tag->id_tag);
        $STH->execute(); 
    }

	public function FindById($id)
    {
        $STH = self::$dbh->prepare('SELECT * FROM tag WHERE id_tag = :id');
        $STH->bindParam(':id', $id);
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');  
        $STH->execute(); 
        return $STH->fetch();
    }

    public function FindAll()
    {
        $STH = self::$dbh->prepare('SELECT * FROM tag');
        $STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');  
        $STH->execute();
        return $STH->fetchAll();
    } 

}

?>
