<?php

class Connector{
    static protected $hostname = '127.0.0.1';
    static protected $username = 'ssws';
    static protected $password = 'sspw';
    static protected $dbname   = 'ssdb';
    static public $conn;

    public function connect(){
        self::$conn = mysqli(self::$hostname,self::$username,self::$password,self::$dbname) or die('');
        return self::$conn;
    }
}

?>