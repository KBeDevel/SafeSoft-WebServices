<?php

class Connector{
    static protected $hostname = '127.0.0.1';
    static protected $username = 'ssws';
    static protected $password = 'sspw';
    static protected $dbname   = 'ssdb';
    static public $link;

    public function connect(){
        include "./init.php";

        self::$link = new msql_init(self::$hostname,self::$username,self::$password,self::$dbname);
        return self::$link;
    }

    public function kill($connection){
        mysqli_close($connection);
    }
}

?>