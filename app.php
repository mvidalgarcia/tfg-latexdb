<?php

class App
{
    protected static $username = 'tfguser';
    protected static $password = 'tfgpass';
    protected static $dsn = "mysql:host=localhost;dbname=tfgdb";

    public static function ConnectDB()
    {
        $dbh = new PDO(self::$dsn, self::$username, self::$password);
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 
        return $dbh;
    }   
}

?>
