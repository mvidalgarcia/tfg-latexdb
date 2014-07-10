<?php

require_once("../model/tag.php");
require_once("../singleton_db.php");

class TagMapper
{
    public static $dbh;
        
	function __construct() 
    {  
		self::$dbh = Database::getConnection();
    }
	

    /* Función que obtiene todos los tags de la base de datos */
	public function FindTagsList()
    {
        $STH = self::$dbh->prepare('SELECT id_tag, nombre  FROM tag');
		$STH->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Tag');
		$STH->execute();
        $tags = $STH->fetchAll();
		return $tags; 
    }

}
?>
