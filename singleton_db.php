<?php

class Database {
	private static $username = 'tfguser';
    private static $password = 'tfgpass';
    private static $dsn = "mysql:host=localhost;dbname=tfgdb";

    private static $db;
    private $connection;

    private function __construct() {
        $this->connection = new PDO(self::$dsn, self::$username, self::$password);
		$this->connection->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
   }

    function __destruct() {
        unset($this->connection);
    }

    public static function getConnection() {
        if (self::$db == null) {
            self::$db = new Database();
        }
        return self::$db->connection;
    }
}
?>
